@extends('layouts.admin')

@section('title')
    {{ __('Groups') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('Group Management') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('groups.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Groups') }}</a>
@endsection

@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__body">
        @include('layouts.inc.alert')

        <div class="mb-4 d-flex justify-content-between align-items-center">
            <a href="{{ route('groups.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{ __('Add Group') }}
            </a>

            <a href="{{ route('groups.import') }}" class="btn btn-success">
                <i class="fa fa-cloud-download"></i> {{ __('Import from Facebook') }}
            </a>
        </div>

        <table class="table" id="kt_table_groups">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Facebook ID') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Privacy') }}</th>
                    <th>{{ __('Active') }}</th>
                    <th>{{ __('Created At') }}</th>
                    <th class="text-center">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groups as $group)
                    <tr>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->fb_group_id }}</td>
                        <td>{{ $group->category->name ?? '-' }}</td>
                        <td><span class="badge badge-info text-uppercase">{{ $group->privacy }}</span></td>
                        <td>
                            @if($group->active)
                                <span class="badge badge-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge badge-secondary">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td>{{ $group->created_at->format('d M Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="Edit">
                                <i class="la la-edit"></i>
                            </a>
                            <a href="#" data-href="{{ route('groups.destroy', $group->id) }}" data-toggle="modal" data-target="#modal-delete"
                               data-key="{{ $group->name }}"
                               class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip"
                               title="Delete"><i class="la la-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $groups->links() }}
        </div>
    </div>
</div>
@endsection

@section('script')
@include('layouts.inc.modal.delete', ['object' => 'group'])
@endsection
