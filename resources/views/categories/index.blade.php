@extends('layouts.admin')

@section('title')
    {{ __('Categories') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('Category Management') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('categories.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Categories') }}</a>
@endsection

@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__body">
        @include('layouts.inc.alert')

        <div class="mb-4 d-flex justify-content-between align-items-center">
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{ __('Add Category') }}
            </a>
        </div>

        <table class="table" id="kt_table_categories">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Created At') }}</th>
                    <th class="text-center">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->created_at->format('d M Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="Edit">
                                <i class="la la-edit"></i>
                            </a>
                            <a href="#" data-href="{{ route('categories.destroy', $category->id) }}" data-toggle="modal" data-target="#modal-delete"
                               data-key="{{ $category->name }}"
                               class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip"
                               title="Delete"><i class="la la-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection

@section('script')
@include('layouts.inc.modal.delete', ['object' => 'category'])
@endsection
