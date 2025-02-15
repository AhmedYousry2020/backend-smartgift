@extends('layouts.dashboard.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>@lang('site.cities')</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
            <li><a href="{{ route('dashboard.cities.index') }}">@lang('site.cities')</a></li>
            <li class="active">@lang('site.add')</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('site.cities')</h3>
            </div>

            <form action="{{ route('dashboard.cities.store') }}" method="POST">
                @csrf
                <div class="box-body">

                    @foreach(config('translatable.locales') as $locale)
                        <div class="form-group">
                            <label>@lang('site.name') ({{ $locale }})</label>
                            <input type="text" name="name[{{ $locale }}]" class="form-control" required>
                        </div>
                    @endforeach
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">@lang('site.add')</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
