@extends('layouts.admin')

@section('title')
    {{ __('Post Details') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Post Details') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('posts.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Posts') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('posts.show', $post->id) }}" class="kt-subheader__breadcrumbs-link">{{ $post->title ?? 'Untitled' }}</a>
@endsection

@section('content')
<div class="kt-portlet" id="kt_page_portlet">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">{{ __('Post Details') }}</h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                <i class="la la-arrow-left"></i>
                <span class="kt-hidden-mobile">{{ __('Back') }}</span>
            </a>
            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary kt-margin-l-10">
                <i class="la la-edit"></i>
                <span class="kt-hidden-mobile">{{ __('Edit') }}</span>
            </a>
            <a href="#" data-href="{{ route('posts.destroy', $post->id) }}" class="btn btn-danger kt-margin-l-10" title="{{ __('Delete') }}" data-toggle="modal" data-target="#modal-delete" data-key="{{ $post->title }}">
                <i class="la la-trash"></i>
                <span class="kt-hidden-mobile">{{ __('Delete') }}</span>
            </a>
        </div>
    </div>

    <div class="kt-portlet__body">
        <div class="kt-section kt-section--first">
            <div class="kt-section__body">
                <div class="row">

                    {{-- Title --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Post Title') }}</label>
                        <input type="text" class="form-control" disabled value="{{ $post->title }}">
                    </div>

                    {{-- Type --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Post Type') }}</label>
                        <input type="text" class="form-control" disabled value="{{ ucfirst($post->type) }}">
                    </div>

                    {{-- Status --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Status') }}</label>
                        <input type="text" class="form-control" disabled value="{{ ucfirst($post->status) }}">
                    </div>

                    {{-- Scheduled Time --}}
                    <div class="form-group col-sm-6">
                        <label>{{ __('Scheduled Time') }}</label>
                        <input type="text" class="form-control" disabled
                               value="{{ $post->scheduled_at ? $post->scheduled_at->format('d M Y H:i') : '-' }}">
                    </div>

                    {{-- Media --}}
                    @if ($post->media_path)
                        <div class="form-group col-sm-12">
                            <label>{{ __('Media File') }}</label><br>
                            @if ($post->type === 'photo')
                                <img src="{{ asset('storage/'.$post->media_path) }}" alt="{{ $post->title }}" style="max-width:100%; height:auto;">
                            @elseif ($post->type === 'video')
                                <video controls style="max-width:100%; height:auto;">
                                    <source src="{{ asset('storage/'.$post->media_path) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        </div>
                    @endif

                    {{-- Content --}}
                    <div class="form-group col-sm-12">
                        <label>{{ __('Post Content') }}</label>
                        <div id="content-editor">{!! $post->content !!}</div>
                    </div>

                    {{-- Groups --}}
                    <div class="form-group col-sm-12">
                        <label>{{ __('Posted To Groups') }}</label>
                        @if($post->schedules->count() > 0)
                            <ul class="list-group">
                                @foreach ($post->schedules as $schedule)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $schedule->group->name ?? '-' }}
                                        <span class="badge badge-success">{{ ucfirst($schedule->status ?? 'scheduled') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>-</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@include('layouts.inc.modal.delete', ['object' => 'post'])

{{-- CKEditor 5 Read-Only --}}
<script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#content-editor'), { readOnly: true })
        .catch(error => console.error(error));
</script>
@endsection
