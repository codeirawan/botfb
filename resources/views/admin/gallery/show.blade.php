@extends('layouts.admin')

@section('title')
    {{ __('Gallery Details') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Gallery Details') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('admin.gallery.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Gallery') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('admin.gallery.show', $gallery->id) }}" class="kt-subheader__breadcrumbs-link">{{ $gallery->name }}</a>
@endsection

@section('content')
<div class="kt-portlet" id="kt_page_portlet">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">{{ __('Gallery Details') }}</h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">
                <i class="la la-arrow-left"></i>
                <span class="kt-hidden-mobile">{{ __('Back') }}</span>
            </a>
            @if (Laratrust::isAbleTo('update-gallery'))
                <a href="{{ route('admin.gallery.edit', $gallery->id) }}" class="btn btn-primary kt-margin-l-10">
                    <i class="la la-edit"></i>
                    <span class="kt-hidden-mobile">{{ __('Edit') }}</span>
                </a>
            @endif
            @if (Laratrust::isAbleTo('delete-gallery'))
                <a href="#" data-href="{{ route('admin.gallery.destroy', $gallery->id) }}" class="btn btn-danger kt-margin-l-10" title="{{ __('Delete') }}" data-toggle="modal" data-target="#modal-delete" data-key="{{ $gallery->name }}">
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
                    {{-- Name --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Gallery Name') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $gallery->name }}">
                    </div>

                    {{-- Image --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Image') }}</label><br>
                        <img src="{{ $gallery->image }}" alt="{{ $gallery->name }}" style="max-width:100%; height:auto;">
                    </div>

                    {{-- Prolog --}}
                    <div class="form-group col-sm-12">
                        <label>{{ __('Prolog') }}</label>
                        <div id="prolog-editor">{!! $gallery->prolog !!}</div>
                    </div>

                    {{-- Details --}}
                    <div class="form-group col-sm-12">
                        <label>{{ __('Details') }}</label>
                        <div id="details-editor">{!! $gallery->details !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@include('layouts.inc.modal.delete', ['object' => 'gallery'])

{{-- CKEditor 5 Read-Only --}}
<script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#prolog-editor'), { readOnly: true })
        .catch(error => console.error(error));

    ClassicEditor
        .create(document.querySelector('#details-editor'), { readOnly: true })
        .catch(error => console.error(error));
</script>
@endsection
