@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @lang('site.settings')
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
                <li><a href="{{route('dashboard.settings.index')}}">@lang('site.settings')</a></li>
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
                     <form action="{{route('dashboard.settings.store')}}" method="post" enctype="multipart/form-data">
                         @csrf
                         @method('post')
                       

                         @foreach(config('translatable.locales') as $locale)

                         <div class="form-group">
                         <!-- site.ar.description-->
                             <label> @lang('site.'.$locale.'.terms_and_conditions')</label>
                         <!--ar[name] -->
                             <textarea class="form-control ckeditor"  name="{{$locale}}[terms_and_conditions]" >{{old($locale.'.terms_and_conditions')}}</textarea>
                         </div>
                         <div class="form-group">
                            <!-- site.ar.description-->
                                <label> @lang('site.'.$locale.'.privacy_policy')</label>
                            <!--ar[name] -->
                                <textarea class="form-control ckeditor"  name="{{$locale}}[privacy_policy]" >{{old($locale.'.privacy_policy')}}</textarea>
                            </div>
                         @endforeach

                       

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.add')</button>
                        </div>
                    </form>

                </div>

            </div>
        </section>
    </div>

@endsection
