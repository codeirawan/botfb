@extends('layouts.admin')

@section('title')
    {{ __('Posts') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('Posts') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('posts.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Posts') }}</a>
@endsection

@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-portlet__content">
                @include('layouts.inc.alert')

                <a href="{{ route('posts.create') }}" class="btn btn-primary mb-4">
                    <i class="fa fa-plus"></i> {{ __('New Post') }}
                </a>

                <table class="table" id="kt_table_posts"></table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.inc.modal.delete', ['object' => 'post'])
    <script src="{{ asset(mix('js/datatable.js')) }}"></script>
    <script src="{{ asset(mix('js/tooltip.js')) }}"></script>

    <script type="text/javascript">
        $('#kt_table_posts').DataTable({
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
                url: '{{ route('posts.data') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                {
                    title: "{{ __('Content') }}",
                    data: 'content',
                    name: 'content',
                    defaultContent: '-',
                    render: function (data, type, row) {
                        if (!data) return '-';
                        return data.length > 50 ? data.substring(0, 50) + '…' : data;
                    }
                },
                {
                    title: "{{ __('Status') }}",
                    data: 'status',
                    name: 'status',
                    class: 'text-center',
                    render: function (data) {
                        let badgeClass = {
                            'draft': 'badge-secondary',
                            'scheduled': 'badge-warning',
                            'posted': 'badge-success'
                        }[data] || 'badge-light';
                        return `<span class="badge ${badgeClass} text-uppercase">${data}</span>`;
                    }
                },
                {
                    title: "{{ __('Scheduled At') }}",
                    data: 'scheduled_at',
                    name: 'scheduled_at',
                    class: 'text-center',
                    defaultContent: '-'
                },
                {
                    title: "{{ __('Action') }}",
                    data: 'action',
                    name: 'action',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    defaultContent: '-'
                }
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        });
    </script>
@endsection
