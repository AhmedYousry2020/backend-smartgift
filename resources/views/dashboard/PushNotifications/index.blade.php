@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @lang('site.notifications')
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
                <li class="active"><i class="fa fa-user"></i>@lang('site.notifications')</li>

            </ol>
        </section>
        <section class="content">
           <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" style="margin-bottom: 15px">@lang('site.notifications') <strong>{{$notifications->count()}}</strong></h3>
                <form action="{{route('dashboard.notifications.index')}}" method="get">
                    <div class="row">
               <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{request()->input('search')}}">
               </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" type="submit" ><i class="fa fa-search"></i>@lang('site.search')</button>
                            <a href="{{route('dashboard.notifications.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.add')</a>

                    </div>
                    </div>
                </form>


            </div>
            <div class="box-body">
                  @if($notifications->count()>0)
                       <table class="table table-bordered table-hover">
                           <thead>
                           <tr>
                               <th>#</th>
                               <th>@lang('site.title')</th>
                               <th>@lang('site.notification_description')</th>
                               <th>@lang('site.registeration_date')</th>
                           </tr>
                           </thead>
                           <tbody>
                           @foreach($notifications as $index => $notification)
                           <tr>
                               <td>{{$index+1}}</td>
                               <td>{{$notification->title}}</td>
                               <td>{{$notification->content}} </td>
                               <td>{{\Carbon\Carbon::parse($notification->created_at)->addHours(config('app.time_diff'))->format('Y-m-d H:i:s')}}</td>

                           </tr>

                           @endforeach
                           </tbody>

                       </table>
                  {{$notifications->appends(request()->query())->links()}}

                   @else
                       <h2>@lang('site.no_data_found')</h2>

                   @endif

               </div>
           </div>
        </section>
    </div>

@endsection
