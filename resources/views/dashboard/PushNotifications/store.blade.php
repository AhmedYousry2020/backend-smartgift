@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @lang('site.notifications')
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
                <li><a href="{{route('dashboard.notifications.index')}}">@lang('site.notifications')</a></li>
                <li class="active">@lang('site.add')</li>

            </ol>
        </section>
        <section class="content">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">@lang('site.add')</h3>
                </div>
                <div class="box-body">
                @include('partials._errors')
                     <form action="{{route('dashboard.notifications.store')}}" method="post">
                         @csrf
                         @method('post')

                         <div class="form-group">
                         <!-- site.ar.name-->
                             <label> @lang('site.title')</label>
                         <!--ar[name] -->
                             <input class="form-control" type="text" name="title" value="{{old('title')}}" >
                         </div>

                         <div class="form-group">
                            <!-- site.ar.description-->
                                <label> @lang('site.description')</label>
                            <!--ar[name] -->
                                <textarea class="form-control ckeditor"  name="description" >{{old('description')}}</textarea>
                            </div>
                         </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.add')</button>
                        </div>
                    </form>

                </div>

            </div>
        </section>
    </div>

@endsection
