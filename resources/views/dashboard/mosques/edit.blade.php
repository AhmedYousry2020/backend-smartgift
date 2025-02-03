@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @lang('site.mosques')
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
                <li><a href="{{route('dashboard.mosques.index')}}">@lang('site.mosques')</a></li>
                <li class="active">@lang('site.edit')</li>

            </ol>
        </section>
        <section class="content">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">@lang('site.edit')</h3>
                </div>
                <div class="box-body">
                    @include('partials._errors')
                    <form action="{{route('dashboard.mosques.update',$mosque->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        @foreach(config('translatable.locales') as $locale)
                         <div class="form-group">
                         <!-- site.ar.name-->
                             <label> @lang('site.'.$locale.'.name')</label>
                         <!--ar[name] -->
                             <input class="form-control" type="text" name="{{$locale}}[name]" value="{{$mosque->translate($locale)->name}}" >
                         </div>

                         @endforeach
                         <div class="form-group">
                            <!-- site.latitude-->
                                <label> @lang('site.latitude')</label>
                            <!--lat -->
                                <input class="form-control" type="number" name="lat" value="{{old('lat',$mosque->lat)}}" step="any" >
                         </div>

                        <div class="form-group">
                            <!-- site.latitude-->
                                <label> @lang('site.longitude')</label>
                            <!--lat -->
                                <input class="form-control" type="number" name="lng" value="{{old('lng',$mosque->lng)}}" step="any">
                        </div>

                        <div class="form-group">
                            <!-- site.latitude-->
                                <label> @lang('site.address')</label>
                            <!--lat -->
                                <input class="form-control" type="text" name="address" value="{{old('address,',$mosque->address)}}" >
                        </div>

                        <div class="form-group">
                            <label> @lang('site.image')</label>
                            <input class="form-control image" type="file" name="image">
                        </div>
                        <div class="form-group">

                            <img src="{{$mosque->image_path}}" style="width:100px" class="img-thumbnail image-preview" alt="">
                        </div>

                         <div class='form-group'>
                            <label>@lang('site.categories')</label>
                            <select name='category_id' class='form-control'>
                           <option value=''>@lang('site.all_categories')</option>
                            @foreach($categories as $category)
                            <option value='{{$category->id}}' <?php if(old('category_id',$mosque->category_id) == $category->id) echo 'selected' ?>>{{$category->name}}</option>
                            @endforeach
                            </select>
                            </div>
                            <div class='form-group'>
                                <label>@lang('site.cities')</label>
                                <select name='city_id' class='form-control'>
                               <option value=''>@lang('site.all_cities')</option>
                                @foreach($cities as $city)
                                <option value='{{$city->id}}' <?php if(old('city_id',$mosque->city_id) == $city->id) echo 'selected' ?>>{{$city->name}}</option>
                                @endforeach
                                </select>
                                </div>



                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i>@lang('site.edit')</button>
                        </div>
                    </form>

                </div>

            </div>
        </section>
    </div>

@endsection
