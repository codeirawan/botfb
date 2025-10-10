<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostReport;
use App\Models\Post;
use App\Models\Group;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PostReportsExport;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    /**
     * Display a list of post reports.
     */
    public function index(Request $request)
    {
        $groups  = Group::active()->get(['id', 'name']);
        $posts   = Post::select('id', 'content');

        return view('reports.index', compact('groups', 'posts'));
    }

    /**
     * Return data for DataTables AJAX.
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $query = PostReport::with(['post', 'group'])
            ->select('post_reports.*')
            ->latest();

            // Optional filter by status
            if ($request->has('status') && in_array($request->status, ['approved', 'pending', 'rejected'])) {
                $query->where('status', $request->status);
            }

            // Optional filter by group
            if ($request->filled('group_id')) {
                $query->where('group_id', $request->group_id);
            }

            // Optional filter by post
            if ($request->filled('post_id')) {
                $query->where('post_id', $request->post_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('post_title', fn($row) => $row->post->content ?? '-')
                ->addColumn('group_name', fn($row) => $row->group->name ?? '-')
                ->editColumn('status', function ($row) {
                    $badgeClass = match ($row->status) {
                        'approved' => 'badge-success',
                        'pending' => 'badge-warning',
                        'rejected' => 'badge-danger',
                        default => 'badge-light',
                    };
                    return "<span class='badge {$badgeClass} text-uppercase'>{$row->status}</span>";
                })
                ->editColumn('message', fn($row) => $row->message ?? '-')
                ->editColumn('created_at', fn($row) => $row->created_at->format('d M Y H:i'))
                ->rawColumns(['status'])
                ->make(true);
        }

        abort(404);
    }

    /**
     * Export post reports to Excel.
     */
    public function export(Request $request)
    {
        $filename = 'post_reports_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new PostReportsExport($request), $filename);
    }
}
