<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostReport;
use App\Models\Post;
use App\Models\Group;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PostReportsExport;

class ReportController extends Controller
{
    /**
     * Display a list of post reports.
     */
    public function index(Request $request)
    {
        $query = PostReport::with(['post', 'group'])
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

        $reports = $query->paginate(10);
        $groups  = Group::active()->get(['id', 'name']);
        $posts   = Post::select('id', 'title')->get();

        return view('reports.index', compact('reports', 'groups', 'posts'));
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
