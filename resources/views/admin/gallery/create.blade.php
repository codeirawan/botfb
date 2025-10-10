@extends('layouts.admin')

@section('title')
    {{ __('Create') }} {{ __('Gallery') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Create') }} {{ __('Gallery') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('admin.gallery.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Gallery') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('admin.gallery.create') }}" class="kt-subheader__breadcrumbs-link">{{ __('Create') }} {{ __('Gallery') }}</a>
@endsection

@section('content')
    <div class="row" data-sticky-container>
        <div class="col-lg-12">
            <form class="kt-form" id="kt_form_1" action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="kt-portlet" id="kt_page_portlet">
                    <div class="kt-portlet__head kt-portlet__head--lg">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">{{ __('Create') }} {{ __('Gallery') }}</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary kt-margin-r-10">
                                <i class="la la-arrow-left"></i>
                                <span class="kt-hidden-mobile">{{ __('Back') }}</span>
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="la la-check"></i>
                                <span class="kt-hidden-mobile">{{ __('Save') }}</span>
                            </button>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                @include('layouts.inc.alert')

                                <div class="row">

                                    {{-- Name --}}
                                    <div class="form-group col-sm-6">
                                        <label for="name">{{ __('Gallery Name') }}</label>
                                        <input id="name" name="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" required
                                            autocomplete="off" placeholder="{{ __('Gallery Name') }}"
                                            value="{{ old('name') }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    {{-- Image --}}
                                    <div class="form-group col-sm-6">
                                        <label for="image">{{ __('Image') }}</label>
                                        <div class="custom-file">
                                            <input id="image" name="image" type="file" accept="image/*"
                                                class="custom-file-input @error('image') is-invalid @enderror">
                                            <label class="custom-file-label" for="image">{{ __('Choose Image') }}</label>
                                            @error('image')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                        <span>{{ __('Recommended size image') }} : 612x408 {{ __('pixels') }}</span>
                                    </div>

                                    {{-- Prolog --}}
                                    <div class="form-group col-sm-12">
                                        <label for="prolog">{{ __('Prolog') }}</label>
                                        <textarea id="prolog" name="prolog" class="form-control @error('prolog') is-invalid @enderror rich-text">{{ old('prolog') }}</textarea>
                                        @error('prolog')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    {{-- Details --}}
                                    <div class="form-group col-sm-12">
                                        <label for="details">{{ __('Details') }}</label>
                                        <textarea id="details" name="details" class="form-control @error('details') is-invalid @enderror rich-text">{{ old('details') }}</textarea>
                                        @error('details')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset(mix('js/form/validation.js')) }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#prolog'))
            .catch(error => console.error(error));

        ClassicEditor
            .create(document.querySelector('#details'))
            .catch(error => console.error(error));
    </script>
@endsection
``
