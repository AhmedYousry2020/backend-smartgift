@extends('layouts.dashboard.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <h1>@lang('site.portfolio_media')</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
            <li class="active">@lang('site.portfolio_media')</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">

            <!-- Media Cards Section -->
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">@lang('site.portfolio_media')</h3>
                    </div>

                    <div class="box-body">
                        @if($portfolioMedia->count() > 0)
                            <div class="row">
                                @foreach ($portfolioMedia as $media)
                                    <div class="col-md-4">
                                        <div class="card">

                                            @if(in_array(pathinfo($media->media_path, PATHINFO_EXTENSION), ['jpg', 'jpeg','JPEG','JPG', 'PNG','png', 'gif']))
                                                <img src="{{ asset($media->media_path) }}" class="img-thumbnail w-100" style="height: 200px; object-fit: cover;" alt="portfolio Media">
                                            @elseif(in_array(pathinfo($media->media_path, PATHINFO_EXTENSION), ['mp4', 'mov', 'avi']))
                                                <video class="card-img-top img-fluid" controls>
                                                    <source src="{{ asset($media->media_path) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif
                                            <div class="card-body text-center">
                                                <form method="POST" action="{{ route('dashboard.portfolio.media.delete', $media->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <h3 class="text-center">@lang('site.no_records')</h3>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upload Media Section -->
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">@lang('site.upload_media')</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{ route('dashboard.portfolio.media.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>@lang('site.choose_file')</label>
                                <input type="file" name="media" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fa fa-upload"></i> @lang('site.upload')
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div><!-- end row -->
    </section><!-- end section -->

</div><!-- end content-wrapper -->

@endsection
