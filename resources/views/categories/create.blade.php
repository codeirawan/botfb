@extends('layouts.admin')

@section('title')
    {{ __('Add Category') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Add Category') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('categories.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Categories') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('categories.create') }}" class="kt-subheader__breadcrumbs-link">{{ __('Add Category') }}</a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <form class="kt-form" action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="kt-portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ __('Add Category') }}</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary kt-margin-r-10">
                            <i class="la la-arrow-left"></i>
                            <span>{{ __('Back') }}</span>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="la la-check"></i>
                            <span>{{ __('Save') }}</span>
                        </button>
                    </div>
                </div>

                <div class="kt-portlet__body">
                    @include('layouts.inc.alert')

                    <div class="row">
                        {{-- Category Name --}}
                        <div class="form-group col-sm-6">
                            <label for="name">{{ __('Category Name') }}</label>
                            <input id="name" name="name" type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
