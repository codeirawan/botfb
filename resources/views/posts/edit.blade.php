@extends('layouts.admin')

@section('title')
    {{ __('Edit Post') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Edit Post') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('posts.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Posts') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('posts.edit', $post->id) }}" class="kt-subheader__breadcrumbs-link">{{ $post->title ?? 'Untitled' }}</a>
@endsection

@section('content')
<div class="row" data-sticky-container>
    <div class="col-lg-12">
        <form class="kt-form" id="kt_form_post" action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="kt-portlet" id="kt_page_portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ __('Edit Post') }}</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary kt-margin-r-10">
                            <i class="la la-arrow-left"></i>
                            <span class="kt-hidden-mobile">{{ __('Back') }}</span>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="la la-check"></i>
                            <span class="kt-hidden-mobile">{{ __('Save Changes') }}</span>
                        </button>
                    </div>
                </div>

                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="kt-section__body">
                            @include('layouts.inc.alert')

                            <div class="row">

                                {{-- Title --}}
                                <div class="form-group col-sm-6">
                                    <label for="title">{{ __('Post Title') }}</label>
                                    <input id="title" name="title" type="text"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $post->title) }}" placeholder="{{ __('Enter post title') }}">
                                    @error('title')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                {{-- Type --}}
                                <div class="form-group col-sm-6">
                                    <label for="type">{{ __('Post Type') }}</label>
                                    <select id="type" name="type" class="form-control @error('type') is-invalid @enderror">
                                        <option value="text" {{ old('type', $post->type) == 'text' ? 'selected' : '' }}>Text</option>
                                        <option value="photo" {{ old('type', $post->type) == 'photo' ? 'selected' : '' }}>Photo</option>
                                        <option value="video" {{ old('type', $post->type) == 'video' ? 'selected' : '' }}>Video</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                {{-- Content --}}
                                <div class="form-group col-sm-12">
                                    <label for="content">{{ __('Post Content') }}</label>
                                    <textarea id="content" name="content" rows="6"
                                        class="form-control rich-text @error('content') is-invalid @enderror">{{ old('content', $post->content) }}</textarea>
                                    @error('content')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                {{-- Media --}}
                                <div class="form-group col-sm-6">
                                    <label for="media">{{ __('Upload Media (Photo/Video)') }}
                                        @if($post->media_path)
                                            <a href="{{ asset('storage/'.$post->media_path) }}" target="_blank"
                                               class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip"
                                               title="{{ __('View Current Media') }}"
                                               style="height: 18px; width: 18px;">
                                               <i class="la la-eye"></i>
                                            </a>
                                        @endif
                                    </label>
                                    <div class="custom-file">
                                        <input id="media" name="media" type="file"
                                            class="custom-file-input @error('media') is-invalid @enderror"
                                            accept="image/*,video/*">
                                        <label class="custom-file-label" for="media">{{ __('Choose File') }}</label>
                                        @error('media')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="form-group col-sm-6">
                                    <label for="status">{{ __('Post Status') }}</label>
                                    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="scheduled" {{ old('status', $post->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="posted" {{ old('status', $post->status) == 'posted' ? 'selected' : '' }}>Posted</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                {{-- Scheduled Time --}}
                                <div class="form-group col-sm-6">
                                    <label for="scheduled_at">{{ __('Scheduled Time') }}</label>
                                    <input id="scheduled_at" name="scheduled_at" type="datetime-local"
                                        class="form-control @error('scheduled_at') is-invalid @enderror"
                                        value="{{ old('scheduled_at', $post->scheduled_at ? $post->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                                    @error('scheduled_at')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                {{-- Groups --}}
                                <div class="form-group col-sm-12">
                                    <label>{{ __('Select Groups to Post') }}</label>
                                    <div class="kt-checkbox-list">
                                        @foreach ($groups as $group)
                                            <label class="kt-checkbox kt-checkbox--success">
                                                <input type="checkbox" name="group_ids[]" value="{{ $group->id }}"
                                                    {{ in_array($group->id, old('group_ids', $post->schedules->pluck('group_id')->toArray() ?? [])) ? 'checked' : '' }}>
                                                {{ $group->name }}
                                                <span></span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                            </div> {{-- end row --}}
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
    document.querySelectorAll('.rich-text').forEach(el => {
        ClassicEditor.create(el).catch(error => console.error(error));
    });
</script>
<style>
    .ck-editor__editable {
        min-height: 200px;
    }
</style>
@endsection
