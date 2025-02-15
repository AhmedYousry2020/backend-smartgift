@extends('layouts.dashboard.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>@lang('site.cities')</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
            <li class="active">@lang('site.cities')</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" style="margin-bottom: 15px">@lang('site.cities') <strong>{{$cities->count()}}</strong></h3>
                <form action="{{route('dashboard.cities.index')}}" method="get">
                    <div class="row">
               <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{request()->input('search')}}">
               </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" type="submit" ><i class="fa fa-search"></i>@lang('site.search')</button>
                            <a href="{{route('dashboard.cities.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.add')</a>

                    </div>
                    </div>
                </form>


            </div>

            <div class="box-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('site.name')</th>
                            <th>@lang('site.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cities as $index => $city)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $city->name }}</td>
                            <td>
                                <a href="{{ route('dashboard.cities.edit', $city->id) }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-edit"></i> @lang('site.edit')
                                </a>
                                <form action="{{ route('dashboard.cities.destroy', $city->id) }}" method="post" style="display: inline-block">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i> @lang('site.delete')
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $cities->links() }}
            </div>
        </div>
    </section>
</div>
@endsection
