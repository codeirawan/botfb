@extends('layouts.admin')

@section('title')
    {{ __('Category Details') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Category Details') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('categories.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Categories') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('categories.show', $category->id) }}" class="kt-subheader__breadcrumbs-link">{{ $category->name }}</a>
@endsection

@section('content')
<div class="kt-portlet" id="kt_page_portlet">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">{{ __('Category Details') }}</h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="la la-arrow-left"></i>
                <span class="kt-hidden-mobile">{{ __('Back') }}</span>
            </a>

            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary kt-margin-l-10">
                <i class="la la-edit"></i>
                <span class="kt-hidden-mobile">{{ __('Edit') }}</span>
            </a>

            <a href="#" data-href="{{ route('categories.destroy', $category->id) }}"
               class="btn btn-danger kt-margin-l-10"
               title="{{ __('Delete') }}" data-toggle="modal"
               data-target="#modal-delete" data-key="{{ $category->name }}">
                <i class="la la-trash"></i>
                <span class="kt-hidden-mobile">{{ __('Delete') }}</span>
            </a>
        </div>
    </div>

    <div class="kt-portlet__body">
        <div class="kt-section kt-section--first">
            <div class="kt-section__body">
                <div class="row">
                    {{-- Category Name --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Category Name') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $category->name }}">
                    </div>

                    {{-- Slug --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Slug') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $category->slug }}">
                    </div>

                    {{-- Description --}}
                    <div class="form-group col-sm-12">
                        <label>{{ __('Description') }}</label>
                        <textarea class="form-control" rows="3" disabled>{{ $category->description ?? '-' }}</textarea>
                    </div>

                    {{-- Active --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Status') }}</label><br>
                        @if($category->active)
                            <span class="badge badge-success">{{ __('Active') }}</span>
                        @else
                            <span class="badge badge-secondary">{{ __('Inactive') }}</span>
                        @endif
                    </div>

                    {{-- Created At --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Created At') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $category->created_at->format('d M Y H:i') }}">
                    </div>

                    {{-- Updated At --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Last Updated') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $category->updated_at->format('d M Y H:i') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@include('layouts.inc.modal.delete', ['object' => 'category'])
@endsection
