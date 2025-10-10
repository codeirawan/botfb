@extends('layouts.admin')

@section('title')
    {{ __('Group Details') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Group Details') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('groups.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Groups') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('groups.show', $group->id) }}" class="kt-subheader__breadcrumbs-link">{{ $group->name }}</a>
@endsection

@section('content')
<div class="kt-portlet" id="kt_page_portlet">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">{{ __('Group Details') }}</h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <a href="{{ route('groups.index') }}" class="btn btn-secondary">
                <i class="la la-arrow-left"></i>
                <span class="kt-hidden-mobile">{{ __('Back') }}</span>
            </a>

            <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-primary kt-margin-l-10">
                <i class="la la-edit"></i>
                <span class="kt-hidden-mobile">{{ __('Edit') }}</span>
            </a>

            <a href="#" data-href="{{ route('groups.destroy', $group->id) }}"
               class="btn btn-danger kt-margin-l-10"
               title="{{ __('Delete') }}" data-toggle="modal"
               data-target="#modal-delete" data-key="{{ $group->name }}">
                <i class="la la-trash"></i>
                <span class="kt-hidden-mobile">{{ __('Delete') }}</span>
            </a>
        </div>
    </div>

    <div class="kt-portlet__body">
        <div class="kt-section kt-section--first">
            <div class="kt-section__body">
                <div class="row">
                    {{-- Group Name --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Group Name') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $group->name }}">
                    </div>

                    {{-- Facebook Group ID --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Facebook Group ID') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $group->fb_group_id }}">
                    </div>

                    {{-- Category --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Category') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $group->category->name ?? '-' }}">
                    </div>

                    {{-- Privacy --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Privacy') }}</label>
                        <input type="text" class="form-control text-capitalize" disabled value="{{ $group->privacy }}">
                    </div>

                    {{-- Active Status --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Active Status') }}</label><br>
                        @if($group->active)
                            <span class="badge badge-success">{{ __('Active') }}</span>
                        @else
                            <span class="badge badge-secondary">{{ __('Inactive') }}</span>
                        @endif
                    </div>

                    {{-- Facebook Account --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Facebook Account ID') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $group->facebook_account_id ?? '-' }}">
                    </div>

                    {{-- Created At --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Created At') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $group->created_at->format('d M Y H:i') }}">
                    </div>

                    {{-- Updated At --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Last Updated') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $group->updated_at->format('d M Y H:i') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@include('layouts.inc.modal.delete', ['object' => 'group'])
@endsection
