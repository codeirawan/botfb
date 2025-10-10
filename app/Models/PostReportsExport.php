<?php

namespace App\Exports;

use App\Models\PostReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Http\Request;

class PostReportsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = PostReport::with(['post', 'group'])
            ->latest();

        if ($this->request->has('status') && in_array($this->request->status, ['approved', 'pending', 'rejected'])) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('group_id')) {
            $query->where('group_id', $this->request->group_id);
        }

        if ($this->request->filled('post_id')) {
            $query->where('post_id', $this->request->post_id);
        }

        return $query->get()->map(function ($report) {
            return [
                'Post Title' => $report->post->title ?? '-',
                'Group Name' => $report->group->name ?? '-',
                'Status' => ucfirst($report->status),
                'Remarks' => $report->remarks ?? '-',
                'Posted At' => optional($report->posted_at)->format('d M Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Post Title',
            'Group Name',
            'Status',
            'Remarks',
            'Posted At',
        ];
    }
}
