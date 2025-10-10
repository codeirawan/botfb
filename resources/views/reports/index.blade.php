@extends('layouts.admin')

@section('title')
    {{ __('Post Reports') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('Post Reports') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('reports.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Reports') }}</a>
@endsection

@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-portlet__content">
                @include('layouts.inc.alert')

                <table class="table" id="kt_table_reports"></table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset(mix('js/datatable.js')) }}"></script>
    <script src="{{ asset(mix('js/tooltip.js')) }}"></script>

    <script type="text/javascript">
        $('#kt_table_reports').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            language: {
                emptyTable: "{{ __('No data available in table') }}",
                info: "{{ __('Showing _START_ to _END_ of _TOTAL_ entries') }}",
                infoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
                infoFiltered: "({{ __('filtered from _MAX_ total entries') }})",
                lengthMenu: "{{ __('Show _MENU_ entries') }}",
                loadingRecords: "{{ __('Loading') }}...",
                processing: "{{ __('Processing') }}...",
                search: "{{ __('Search') }}",
                zeroRecords: "{{ __('No matching records found') }}"
            },
            ajax: {
                method: 'POST',
                url: '{{ route('reports.data') }}', // kamu buat route ini nanti
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                {
                    title: "{{ __('Post') }}",
                    data: 'post_title',
                    name: 'post.content',
                    defaultContent: '-'
                },
                {
                    title: "{{ __('Group') }}",
                    data: 'group_name',
                    name: 'group.name',
                    defaultContent: '-'
                },
                {
                    title: "{{ __('Status') }}",
                    data: 'status',
                    name: 'status',
                    class: 'text-center',
                    render: function (data) {
                        let badgeClass = {
                            'success': 'badge-success',
                            'failed': 'badge-danger',
                            'pending': 'badge-warning'
                        }[data] || 'badge-light';
                        return `<span class="badge ${badgeClass} text-uppercase">${data}</span>`;
                    }
                },
                {
                    title: "{{ __('Message') }}",
                    data: 'message',
                    name: 'message',
                    defaultContent: '-',
                    render: function (data) {
                        if (!data) return '-';
                        return data.length > 80 ? data.substring(0, 80) + '…' : data;
                    }
                },
                {
                    title: "{{ __('Created At') }}",
                    data: 'created_at',
                    name: 'created_at',
                    class: 'text-center',
                    defaultContent: '-'
                }
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        });
    </script>
@endsection
