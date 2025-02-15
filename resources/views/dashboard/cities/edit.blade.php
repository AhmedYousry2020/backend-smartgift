@extends('layouts.dashboard.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>@lang('site.edit_city')</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
            <li><a href="{{ route('dashboard.cities.index') }}">@lang('site.cities')</a></li>
            <li class="active">@lang('site.edit')</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('site.edit_city')</h3>
            </div>

            <form action="{{ route('dashboard.cities.update', $city->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="box-body">
             

                    @foreach(config('translatable.locales') as $locale)
                        <div class="form-group">
                            <label>@lang('site.name') ({{ $locale }})</label>
                            <input type="text" name="name[{{ $locale }}]" class="form-control"
                                   value="{{ $city->translate($locale)->name ?? '' }}" required>
                        </div>
                    @endforeach
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">@lang('site.update')</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
