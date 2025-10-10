@extends('layouts.admin')

@section('title')
    {{ __('Gallery') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('Gallery') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('admin.gallery.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Gallery') }}</a>
@endsection

@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-portlet__content">
                @include('layouts.inc.alert')

                @if (Laratrust::isAbleTo('create-gallery'))
                    <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary mb-4">
                        <i class="fa fa-plus"></i> {{ __('New Gallery') }}
                    </a>
                @endif

                <table class="table" id="kt_table_1"></table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.inc.modal.delete', ['object' => 'gallery'])
    <script src="{{ asset(mix('js/datatable.js')) }}"></script>
    <script src="{{ asset(mix('js/tooltip.js')) }}"></script>
    <script type="text/javascript">
        $('#kt_table_1').DataTable({
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
                url: '{{ route('admin.gallery.data') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                {
                    title: "{{ __('Image') }}",
                    data: 'image',
                    name: 'image',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Name') }}",
                    data: 'name',
                    name: 'name',
                    defaultContent: '-',
                },
                // {
                //     title: "{{ __('Prolog') }}",
                //     data: 'prolog',
                //     name: 'prolog',
                //     defaultContent: '-',
                // },
                // {
                //     title: "{{ __('Details') }}",
                //     data: 'details',
                //     name: 'details',
                //     defaultContent: '-',
                // },
                {
                    title: "{{ __('Action') }}",
                    data: 'action',
                    name: 'action',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                }
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        });
    </script>
@endsection
