@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @lang('site.settings')
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
                <li class="active"><i class="fa fa-user"></i>@lang('site.settings')</li>

            </ol>
        </section>
        <section class="content">
           <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" style="margin-bottom: 15px">@lang('site.settings') <strong>{{$settings->count()}}</strong></h3>
                <form action="{{route('dashboard.settings.index')}}" method="get">
                    <div class="row">
               <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{request()->input('search')}}">
               </div>
              

                        <div class="col-md-4">
                            <button class="btn btn-primary" type="submit" ><i class="fa fa-search"></i>@lang('site.search')</button>
                            <a href="{{route('dashboard.settings.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.add')</a>


                    </div>
                </form>


            </div>
            <div class="box-body table-responsive">
                  @if($settings->count()>0)
                       <table class="table table-bordered table-hover">
                           <thead>
                           <tr>
                               <th>#</th>
                               <th>@lang('site.terms_and_conditions')</th>
                               <th>@lang('site.privacy_policy')</th>
                               <th>@lang('site.action')</th>
                           </tr>
                           </thead>
                           <tbody>
                           @foreach($settings as $index=>$setting)
                           <tr>
                               <td>{{$index+1}}</td>
                               <td>{!! $setting->terms_and_conditions !!}</td>
                               <td>{!! $setting->privacy_policy !!}</td>
                            
                        

                               <td>
                                       <a href="{{route('dashboard.settings.edit',$setting->id)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i>@lang('site.edit')</a>
                                   <form method="post" class="delete" action="{{route('dashboard.settings.destroy',$setting->id)}}" style="display: inline-block">
                                       @csrf
                                       @method('delete')
                                       <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>@lang('site.delete')</button>
                                   </form>

                               </td>
                           </tr>

                           @endforeach
                           </tbody>

                       </table>
                   @else
                       <h2>@lang('site.no_data_found')</h2>

                   @endif

               </div>
           </div>
        </section>
    </div>

@endsection
