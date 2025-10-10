@extends('layouts.admin')

@section('title')
    {{ __('Add Group') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Add Group') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('groups.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Groups') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('groups.create') }}" class="kt-subheader__breadcrumbs-link">{{ __('Add Group') }}</a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <form class="kt-form" action="{{ route('groups.store') }}" method="POST">
            @csrf
            <div class="kt-portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ __('Add Group') }}</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ route('groups.index') }}" class="btn btn-secondary kt-margin-r-10">
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
                        <div class="form-group col-sm-6">
                            <label for="name">{{ __('Group Name') }}</label>
                            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="fb_group_id">{{ __('Facebook Group ID') }}</label>
                            <input id="fb_group_id" name="fb_group_id" type="text" class="form-control @error('fb_group_id') is-invalid @enderror"
                                   value="{{ old('fb_group_id') }}" required>
                            @error('fb_group_id')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="category_id">{{ __('Category') }}</label>
                            <select id="category_id" name="category_id" class="form-control">
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="privacy">{{ __('Privacy') }}</label>
                            <select id="privacy" name="privacy" class="form-control">
                                <option value="public">Public</option>
                                <option value="closed">Closed</option>
                                <option value="secret">Secret</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-6">
                            <label>{{ __('Active') }}</label>
                            <div class="kt-checkbox-list">
                                <label class="kt-checkbox kt-checkbox--success">
                                    <input type="checkbox" name="active" value="1" checked>
                                    {{ __('Active Group') }}
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
