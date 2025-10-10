@extends('layouts.admin')

@section('title')
    {{ __('Profile Details') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Profile Details') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('admin.profile.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Profiles') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('admin.profile.show', $profile->id) }}" class="kt-subheader__breadcrumbs-link">{{ $profile->name }}</a>
@endsection

@section('content')
<div class="kt-portlet" id="kt_page_portlet">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">{{ __('Profile Details') }}</h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary">
                <i class="la la-arrow-left"></i>
                <span class="kt-hidden-mobile">{{ __('Back') }}</span>
            </a>
            @if (Laratrust::isAbleTo('update-profile'))
                <a href="{{ route('admin.profile.edit', $profile->id) }}" class="btn btn-primary kt-margin-l-10">
                    <i class="la la-edit"></i>
                    <span class="kt-hidden-mobile">{{ __('Edit') }}</span>
                </a>
            @endif
            @if (Laratrust::isAbleTo('delete-profile'))
                <a href="#" data-href="{{ route('admin.profile.destroy', $profile->id) }}" class="btn btn-danger kt-margin-l-10" title="{{ __('Delete') }}" data-toggle="modal" data-target="#modal-delete" data-key="{{ $profile->name }}">
                    <i class="la la-trash"></i>
                    <span class="kt-hidden-mobile">{{ __('Delete') }}</span>
                </a>
            @endif
        </div>
    </div>

    <div class="kt-portlet__body">
        <div class="kt-section kt-section--first">
            <div class="kt-section__body">
                <div class="row">
                    {{-- Photo --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Photo') }}</label><br>
                        @if($profile->photo)
                            <img src="{{ asset($profile->photo) }}" alt="{{ $profile->name }}" style="max-width:100%; height:auto; border-radius:5px;">
                        @else
                            <span>{{ __('No photo available') }}</span>
                        @endif
                    </div>

                    {{-- Name --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Name') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $profile->name }}">
                    </div>

                    {{-- Address --}}
                    <div class="form-group col-sm-12">
                        <label>{{ __('Address') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $profile->address }}">
                    </div>

                    {{-- Portfolio --}}
                    <div class="form-group col-sm-12">
                        <label>{{ __('Portfolio') }}</label>
                        <div id="portfolio-editor">{!! $profile->portfolio !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@include('layouts.inc.modal.delete', ['object' => 'profile'])

{{-- CKEditor 5 Read-Only --}}
<script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#portfolio-editor'), { readOnly: true })
        .catch(error => console.error(error));
</script>
@endsection
