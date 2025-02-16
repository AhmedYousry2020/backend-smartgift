@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @lang('site.categories')
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
                <li class="active"><i class="fa fa-user"></i>@lang('site.categories')</li>

            </ol>
        </section>
        <section class="content">
           <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" style="margin-bottom: 15px">@lang('site.categories') <strong>{{$categories->count()}}</strong></h3>
                <form action="{{route('dashboard.categories.index')}}" method="get">
                    <div class="row">
               <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{request()->input('search')}}">
               </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" type="submit" ><i class="fa fa-search"></i>@lang('site.search')</button>
                            <a href="{{route('dashboard.categories.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.add')</a>

                    </div>
                    </div>
                </form>


            </div>
            <div class="box-body table-responsive">
                  @if($categories->count()>0)
                       <table class="table table-bordered table-hover">
                           <thead>
                           <tr>
                               <th>#</th>
                               <th>@lang('site.name')</th>
                               <th>@lang('site.description')</th>
                               <th>@lang('site.mosques_count')</th>
                               <th>@lang('site.related_mosques')</th>
                               <th>@lang('site.action')</th>
                           </tr>
                           </thead>
                           <tbody>
                           @foreach($categories as $index=>$category)
                           <tr>
                               <td>{{$index+1}}</td>
                               <td>{{$category->name}}</td>
                               <td>{{$category->description}}</td>
                               <td>{{$category->mosques->count()}} </td>
                               <td><a href="{{route('dashboard.mosques.index',['category_id'=>$category->id])}}" class="btn btn-info sm">@lang('site.related_mosques')</a></td>
                               <td>
                                       <a href="{{route('dashboard.categories.edit',$category->id)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i>@lang('site.edit')</a>
                                   <form method="post" class="delete" action="{{route('dashboard.categories.destroy',$category->id)}}" style="display: inline-block">
                                       @csrf
                                       @method('delete')
                                       <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>@lang('site.delete')</button>
                                   </form>
                               </td>
                           </tr>

                           @endforeach
                           </tbody>

                       </table>
                  {{$categories->appends(request()->query())->links()}}

                   @else
                       <h2>@lang('site.no_data_found')</h2>

                   @endif

               </div>
           </div>
        </section>
    </div>

@endsection
