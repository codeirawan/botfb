@extends('layouts.admin')

@section('title')
    {{ __('Edit') }} {{ __('Profile') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Edit') }} {{ __('Profile') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('admin.profile.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Profiles') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('admin.profile.edit', $profile->id) }}" class="kt-subheader__breadcrumbs-link">{{ __('Edit') }} {{ __('Profile') }}</a>
@endsection

@section('content')
    <div class="row" data-sticky-container>
        <div class="col-lg-12">
            <form class="kt-form" id="kt_form_1" action="{{ route('admin.profile.update', $profile->id) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <div class="kt-portlet" id="kt_page_portlet">
                    <div class="kt-portlet__head kt-portlet__head--lg">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">{{ __('Edit') }} {{ __('Profile') }}</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary kt-margin-r-10">
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

                                    {{-- Photo --}}
                                    <div class="form-group col-sm-6">
                                        <label for="photo">{{ __('Photo') }}
                                            @if($profile->photo)
                                                <a href="{{ asset($profile->photo) }}" target="_blank"
                                                   class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip"
                                                   title="{{ __('View') }} {{ __('Photo') }}"
                                                   style="height: 18px; width: 18px;">
                                                    <i class="la la-eye"></i>
                                                </a>
                                            @endif
                                        </label>
                                        <div class="custom-file">
                                            <input id="photo" name="photo" type="file" accept="image/*"
                                                class="custom-file-input @error('photo') is-invalid @enderror">
                                            <label class="custom-file-label" for="photo">{{ __('Choose Photo') }}</label>
                                            @error('photo')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                        <span>{{ __('Recommended size') }}: 300x300 {{ __('pixels') }}</span>
                                    </div>

                                    {{-- Name --}}
                                    <div class="form-group col-sm-6">
                                        <label for="name">{{ __('Name') }}</label>
                                        <input id="name" name="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" required
                                            autocomplete="off" placeholder="{{ __('Name') }}" value="{{ old('name', $profile->name) }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    {{-- Address --}}
                                    <div class="form-group col-sm-6">
                                        <label for="address">{{ __('Address') }}</label>
                                        <input id="address" name="address" type="text"
                                            class="form-control @error('address') is-invalid @enderror"
                                            autocomplete="off" placeholder="{{ __('Address') }}" value="{{ old('address', $profile->address) }}">
                                        @error('address')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    {{-- Portfolio --}}
                                    <div class="form-group col-sm-12">
                                        <label for="portfolio">{{ __('Portfolio') }}</label>
                                        <textarea id="portfolio" name="portfolio"
                                            class="form-control @error('portfolio') is-invalid @enderror rich-text">{{ old('portfolio', $profile->portfolio) }}</textarea>
                                        @error('portfolio')
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
            .create(document.querySelector('#portfolio'))
            .catch(error => console.error(error));
    </script>
@endsection
