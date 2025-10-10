<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Post;
use App\Models\PostSchedule;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;


class PostController extends Controller
{
    /**
     * Display a list of all posts.
     */
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $query = Post::query()->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('content', function ($row) {
                    return $row->content
                        ? (strlen($row->content) > 50
                            ? substr($row->content, 0, 50) . '…'
                            : $row->content)
                        : '-';
                })
                ->editColumn('status', function ($row) {
                    $class = match ($row->status) {
                        'draft' => 'badge-secondary',
                        'scheduled' => 'badge-warning',
                        'posted' => 'badge-success',
                        default => 'badge-light',
                    };
                    return '<span class="badge ' . $class . '">' . ucfirst($row->status) . '</span>';
                })
                ->editColumn('scheduled_at', function ($row) {
                    return $row->scheduled_at
                        ? Carbon::parse($row->scheduled_at)->format('d M Y H:i')
                        : '-';
                })

                ->addColumn('action', function ($row) {
                    $editUrl = route('posts.edit', $row->id);
                    $deleteUrl = route('posts.destroy', $row->id);
                    return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip"
                        title="Edit Post"><i class="la la-edit"></i></a>
                    <a href="#" data-href="' . $deleteUrl . '" data-toggle="modal" data-target="#modal-delete"
                        data-key="Post ID: ' . $row->id . '"
                        class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip"
                        title="Delete"><i class="la la-trash"></i></a>
                ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        abort(404);
    }


    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $groups = Group::active()->get();
        return view('posts.create', compact('groups'));
    }

    /**
     * Store a newly created post in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string',
            'media' => 'nullable|file|max:5120', // 5MB
            'schedule_type' => 'required|in:now,once,repeat',
            'scheduled_at' => 'nullable|date',
            'repeat_times.*' => 'nullable|date',
            'group_ids' => 'nullable|array',
            'action' => 'nullable|in:draft,schedule,publish',
        ]);

        DB::transaction(function () use ($request) {
            $post = new Post();
            $post->content = $request->content;
            $post->status = 'draft'; // default

            // Upload media if exists
            if ($request->hasFile('media')) {
                $path = $request->file('media')->store('uploads/posts', 'public');
                $post->media_path = $path;
            }

            // Determine status based on button action
            switch ($request->action) {
                case 'draft':
                    $post->status = 'draft';
                    break;

                case 'schedule':
                    if ($request->schedule_type === 'once' && $request->filled('scheduled_at')) {
                        $post->status = 'scheduled';
                        $post->scheduled_at = Carbon::parse($request->scheduled_at);
                    } elseif ($request->schedule_type === 'repeat') {
                        $post->status = 'scheduled';
                    }
                    break;

                case 'publish':
                    $post->status = 'posted';
                    $post->scheduled_at = now();
                    break;
            }

            $post->save();

            // Handle repeat schedules
            if ($post->status === 'scheduled' && is_array($request->repeat_times)) {
                foreach ($request->repeat_times as $time) {
                    if (!empty($time)) {
                        PostSchedule::create([
                            'post_id' => $post->id,
                            'scheduled_at' => Carbon::parse($time),
                        ]);
                    }
                }
            }

            // Attach groups
            if ($request->filled('group_ids')) {
                $post->groups()->sync($request->group_ids);
            }
        });

        return redirect()->route('posts.index')->with('success', 'Post saved successfully.');
    }


    /**
     * Show the form for editing an existing post.
     */
    public function edit(Post $post)
    {
        $groups = Group::active()->get();
        $repeatTimes = PostSchedule::where('post_id', $post->id)->pluck('scheduled_at');
        return view('posts.edit', compact('post', 'groups', 'repeatTimes'));
    }

    /**
     * Update an existing post.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'nullable|string',
            'media' => 'nullable|file|max:5120',
            'schedule_type' => 'required|in:now,once,repeat',
            'scheduled_at' => 'nullable|date',
            'repeat_times.*' => 'nullable|date',
            'group_ids' => 'nullable|array',
            'action' => 'nullable|in:draft,schedule,publish',
        ]);

        DB::transaction(function () use ($request, $post) {
            $post->content = $request->content;

            // Replace media if uploaded
            if ($request->hasFile('media')) {
                if ($post->media_path && Storage::disk('public')->exists($post->media_path)) {
                    Storage::disk('public')->delete($post->media_path);
                }
                $path = $request->file('media')->store('uploads/posts', 'public');
                $post->media_path = $path;
            }

            // Reset
            $post->status = 'draft';
            $post->scheduled_at = null;

            // Determine status based on button
            switch ($request->action) {
                case 'draft':
                    $post->status = 'draft';
                    break;

                case 'schedule':
                    if ($request->schedule_type === 'once' && $request->filled('scheduled_at')) {
                        $post->status = 'scheduled';
                        $post->scheduled_at = Carbon::parse($request->scheduled_at);
                    } elseif ($request->schedule_type === 'repeat') {
                        $post->status = 'scheduled';
                    }
                    break;

                case 'publish':
                    $post->status = 'posted';
                    $post->scheduled_at = now();
                    break;
            }

            $post->save();

            // Update repeat schedules
            PostSchedule::where('post_id', $post->id)->delete();
            if ($post->status === 'scheduled' && is_array($request->repeat_times)) {
                foreach ($request->repeat_times as $time) {
                    if (!empty($time)) {
                        PostSchedule::create([
                            'post_id' => $post->id,
                            'scheduled_at' => Carbon::parse($time),
                        ]);
                    }
                }
            }

            // Sync groups
            if ($request->filled('group_ids')) {
                $post->groups()->sync($request->group_ids);
            }
        });

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Delete a post and its media file.
     */
    public function destroy(Post $post)
    {
        if ($post->media_path && Storage::disk('public')->exists($post->media_path)) {
            Storage::disk('public')->delete($post->media_path);
        }

        $post->delete();
        PostSchedule::where('post_id', $post->id)->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    /**
     * Drafts view.
     */
    public function drafts()
    {
        $posts = Post::where('status', 'draft')->latest()->paginate(10);
        return view('posts.drafts', compact('posts'));
    }

    /**
     * Scheduled posts view.
     */
    public function schedule()
    {
        $posts = Post::where('status', 'scheduled')->latest()->paginate(10);
        return view('posts.schedule', compact('posts'));
    }
}
