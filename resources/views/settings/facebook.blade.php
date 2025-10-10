@extends('layouts.admin')

@section('title')
    {{ __('Facebook Settings') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Facebook Settings') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('settings.facebook') }}" class="kt-subheader__breadcrumbs-link">{{ __('Settings') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <span class="kt-subheader__breadcrumbs-link">{{ __('Facebook') }}</span>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            {{-- Update form --}}
            <form class="kt-form" action="{{ route('settings.facebook.update') }}" method="POST">
                @csrf
                <div class="kt-portlet">
                    {{-- Header --}}
                    <div class="kt-portlet__head kt-portlet__head--lg">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">{{ __('Facebook API Configuration') }}</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary kt-margin-r-10">
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('Back') }}</span>
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="la la-check"></i>
                                <span>{{ __('Save') }}</span>
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="kt-portlet__body">
                        @include('layouts.inc.alert')

                        <div class="row">
                            {{-- Name --}}
                            <div class="form-group col-sm-6">
                                <label for="name">{{ __('Name') }}</label>
                                <input id="name" name="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $account->name ?? '') }}" required>
                                @error('name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- Facebook User ID --}}
                            <div class="form-group col-sm-6">
                                <label for="fb_user_id">{{ __('Facebook User ID') }}</label>
                                <input id="fb_user_id" name="fb_user_id" type="text"
                                    class="form-control @error('fb_user_id') is-invalid @enderror"
                                    value="{{ old('fb_user_id', $account->fb_user_id ?? '') }}" required>
                                @error('fb_user_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- App ID --}}
                            <div class="form-group col-sm-6">
                                <label for="app_id">{{ __('Facebook App ID') }}</label>
                                <input id="app_id" name="app_id" type="text"
                                    class="form-control @error('app_id') is-invalid @enderror"
                                    value="{{ old('app_id', $account->app_id ?? '') }}" required>
                                @error('app_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- App Secret --}}
                            <div class="form-group col-sm-6">
                                <label for="app_secret">{{ __('Facebook App Secret') }}</label>
                                <input id="app_secret" name="app_secret" type="text"
                                    class="form-control @error('app_secret') is-invalid @enderror"
                                    value="{{ old('app_secret', $account->app_secret ?? '') }}" required>
                                @error('app_secret')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- Access Token --}}
                            <div class="form-group col-sm-12">
                                <label for="access_token">{{ __('Access Token') }}</label>
                                <textarea id="access_token" name="access_token" rows="3"
                                    class="form-control @error('access_token') is-invalid @enderror" required>{{ old('access_token', $account->access_token ?? '') }}</textarea>
                                @error('access_token')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="la la-check"></i> {{ __('Save Changes') }}
                            </button>

                            {{-- Test Connection Form --}}
                            <form action="{{ route('settings.facebook.test') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="la la-plug"></i> {{ __('Test Connection') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
