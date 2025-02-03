@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @lang('site.mosques')
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
                <li class="active"><i class="fa fa-user"></i>@lang('site.mosques')</li>

            </ol>
        </section>
        <section class="content">
           <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" style="margin-bottom: 15px">@lang('site.mosques') <strong>{{$mosques->count()}}</strong></h3>
                <form action="{{route('dashboard.mosques.index')}}" method="get">
                    <div class="row">
               <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{request()->input('search')}}">
               </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" type="submit" ><i class="fa fa-search"></i>@lang('site.search')</button>
                            <a href="{{route('dashboard.mosques.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.add')</a>

                    </div>
                    </div>
                </form>


            </div>
            <div class="box-body">
                  @if($mosques->count()>0)
                       <table class="table table-bordered table-hover">
                           <thead>
                           <tr>
                               <th>#</th>
                               <th>@lang('site.name')</th>
                               <th>@lang('site.address')</th>
                               <th>@lang('site.image')</th>
                               <th>@lang('site.category')</th>
                               <th>@lang('site.action')</th>
                           </tr>
                           </thead>
                           <tbody>
                           @foreach($mosques as $index=>$mosque)
                           <tr>
                               <td>{{$index+1}}</td>
                               <td>{{$mosque->name}}</td>
                               <td>{{$mosque->address}}</td>
                               <td><img src="{{$mosque->image_path}}" alt="" style="width: 90px;" class="img-thumbnail"></td>

                               <td>{{$mosque->category->name}} </td>
                               <td>
                                       <a href="{{route('dashboard.mosques.edit',$mosque->id)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i>@lang('site.edit')</a>
                                   <form method="post" class="delete" action="{{route('dashboard.mosques.destroy',$mosque->id)}}" style="display: inline-block">
                                       @csrf
                                       @method('delete')
                                       <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>@lang('site.delete')</button>
                                   </form>
                               </td>
                           </tr>

                           @endforeach
                           </tbody>

                       </table>
                  {{$mosques->appends(request()->query())->links()}}

                   @else
                       <h2>@lang('site.no_data_found')</h2>

                   @endif

               </div>
           </div>
        </section>
    </div>

@endsection
