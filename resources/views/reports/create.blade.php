@extends('layouts.admin')

@section('title')
    {{ __('Create Post') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Create Post') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('posts.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Posts') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('posts.create') }}" class="kt-subheader__breadcrumbs-link">{{ __('Create Post') }}</a>
@endsection

@section('content')
    <div class="row" data-sticky-container>
        <div class="col-lg-12">
            <form class="kt-form" id="kt_form_post" action="{{ route('posts.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" id="post_action" value="draft">

                <div class="kt-portlet" id="kt_page_portlet">
                    <div class="kt-portlet__head kt-portlet__head--lg">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">{{ __('Create Post') }}</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary kt-margin-r-10">
                                <i class="la la-arrow-left"></i>
                                <span class="kt-hidden-mobile">{{ __('Back') }}</span>
                            </a>
                            <div class="btn-group">
                                <button type="submit" class="btn btn-warning" onclick="setAction('draft')">
                                    <i class="la la-save"></i>
                                    <span class="kt-hidden-mobile">{{ __('Save as Draft') }}</span>
                                </button>
                                <button type="submit" class="btn btn-success" onclick="setAction('schedule')">
                                    <i class="la la-clock-o"></i>
                                    <span class="kt-hidden-mobile">{{ __('Schedule Post') }}</span>
                                </button>
                                <button type="submit" class="btn btn-primary" onclick="setAction('publish')">
                                    <i class="la la-paper-plane"></i>
                                    <span class="kt-hidden-mobile">{{ __('Post Now') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                @include('layouts.inc.alert')

                                <div class="row">

                                    {{-- Post Content --}}
                                    <div class="form-group col-sm-12">
                                        <label for="content">{{ __('Post Content') }}</label>
                                        <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" rows="6"
                                            placeholder="Write your post here...">{{ old('content') }}</textarea>
                                        @error('content')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    {{-- Media Upload --}}
                                    <div class="form-group col-sm-12">
                                        <label for="media">{{ __('Upload Media (Photo/Video)') }}</label>
                                        <div class="custom-file">
                                            <input id="media" name="media" type="file"
                                                class="custom-file-input @error('media') is-invalid @enderror"
                                                accept="image/*,video/*">
                                            <label class="custom-file-label" for="media">{{ __('Choose file') }}</label>
                                            @error('media')
                                                <span class="invalid-feedback"
                                                    role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Schedule Type --}}
                                    <div class="form-group col-sm-6">
                                        <label for="schedule_type">{{ __('Schedule Type') }}</label>
                                        <select id="schedule_type" name="schedule_type"
                                            class="form-control @error('schedule_type') is-invalid @enderror" required>
                                            <option value="now" {{ old('schedule_type') == 'now' ? 'selected' : '' }}>
                                                Post Now</option>
                                            <option value="once" {{ old('schedule_type') == 'once' ? 'selected' : '' }}>
                                                Schedule Once</option>
                                            <option value="repeat"
                                                {{ old('schedule_type') == 'repeat' ? 'selected' : '' }}>Repeat Schedule
                                            </option>
                                        </select>
                                        @error('schedule_type')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    {{-- Schedule Once --}}
                                    <div class="form-group col-sm-6" id="schedule_once_wrapper">
                                        <label for="scheduled_at">{{ __('Scheduled Time (if applicable)') }}</label>
                                        <input id="scheduled_at" name="scheduled_at" type="datetime-local"
                                            class="form-control @error('scheduled_at') is-invalid @enderror"
                                            value="{{ old('scheduled_at') }}">
                                        @error('scheduled_at')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    {{-- Repeat Schedule --}}
                                    <div class="form-group col-sm-6 d-none" id="repeat_schedule_wrapper">
                                        <label>{{ __('Repeat Posting Times') }}</label>
                                        <div id="repeat_times">
                                            <div class="input-group mb-2 repeat-time-item">
                                                <input type="datetime-local" name="repeat_times[]" class="form-control" />
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-remove-time">
                                                        <i class="la la-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" id="btn_add_time"
                                            class="btn btn-outline-primary btn-sm mt-2">
                                            <i class="la la-plus"></i> {{ __('Add Another Time') }}
                                        </button>
                                    </div>

                                    {{-- Groups --}}
                                    <div class="form-group col-sm-12">
                                        <label>{{ __('Select Groups to Post') }}</label>
                                        <div class="kt-checkbox-list">
                                            @foreach ($groups as $group)
                                                <label class="kt-checkbox kt-checkbox--success">
                                                    <input type="checkbox" name="group_ids[]"
                                                        value="{{ $group->id }}">
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
    <script>
        const scheduleType = document.getElementById('schedule_type');
        const repeatWrapper = document.getElementById('repeat_schedule_wrapper');
        const onceWrapper = document.getElementById('schedule_once_wrapper');
        const btnAdd = document.getElementById('btn_add_time');
        const repeatTimes = document.getElementById('repeat_times');
        const actionInput = document.getElementById('post_action');

        // Set hidden action input before submit
        function setAction(action) {
            actionInput.value = action;
        }

        // Toggle visibility for schedule types
        scheduleType.addEventListener('change', () => {
            if (scheduleType.value === 'repeat') {
                repeatWrapper.classList.remove('d-none');
                onceWrapper.classList.add('d-none');
            } else if (scheduleType.value === 'once') {
                repeatWrapper.classList.add('d-none');
                onceWrapper.classList.remove('d-none');
            } else {
                repeatWrapper.classList.add('d-none');
                onceWrapper.classList.add('d-none');
            }
        });

        // Add new repeat time input
        btnAdd.addEventListener('click', () => {
            const wrapper = document.createElement('div');
            wrapper.classList.add('input-group', 'mb-2', 'repeat-time-item');
            wrapper.innerHTML = `
            <input type="datetime-local" name="repeat_times[]" class="form-control" />
            <div class="input-group-append">
                <button type="button" class="btn btn-danger btn-remove-time">
                    <i class="la la-trash"></i>
                </button>
            </div>
        `;
            repeatTimes.appendChild(wrapper);
        });

        // Remove specific repeat time
        repeatTimes.addEventListener('click', (e) => {
            if (e.target.closest('.btn-remove-time')) {
                e.target.closest('.repeat-time-item').remove();
            }
        });

        // Initial display setup
        scheduleType.dispatchEvent(new Event('change'));
    </script>
@endsection
