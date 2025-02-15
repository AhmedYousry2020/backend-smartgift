@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @lang('site.products')
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
                <li class="active"><i class="fa fa-user"></i>@lang('site.products')</li>

            </ol>
        </section>
        <section class="content">
           <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" style="margin-bottom: 15px">@lang('site.products') <strong>{{$products->count()}}</strong></h3>
                <form action="{{route('dashboard.products.index')}}" method="get">
                    <div class="row">
               <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{request()->input('search')}}">
               </div>
               <div class='col-md-4'>

                         <select name='company_id' class='form-control'>
                        <option value=''>@lang('site.all_categories')</option>
                         @foreach($companies as $company)
                         <option value='{{$company->id}}' <?php if(request()->input('company_id') == $company->id ) echo 'selected'  ?>>{{$company->name}}</option>
                         @endforeach
                         </select>
                         </div>

                        <div class="col-md-4">
                            <button class="btn btn-primary" type="submit" ><i class="fa fa-search"></i>@lang('site.search')</button>
                            <a href="{{route('dashboard.products.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.add')</a>


                    </div>
                </form>


            </div>
            <div class="box-body table-responsive">
                  @if($products->count()>0)
                       <table class="table table-bordered table-hover">
                           <thead>
                           <tr>
                               <th>#</th>
                               <th>@lang('site.name')</th>
                               <th>@lang('site.description')</th>
                               <th>@lang('site.category')</th>
                               <th>@lang('site.image')</th>
                               <th>@lang('site.purchase_price')</th>
                               <th>@lang('site.sale_price')</th>
                               <th>@lang('site.bottle_count')</th>

                               <th>@lang('site.action')</th>
                           </tr>
                           </thead>
                           <tbody>
                           @foreach($products as $index=>$product)
                           <tr>
                               <td>{{$index+1}}</td>
                               <td>{{$product->name}}</td>
                               <td>{!! $product->description !!}</td>
                               <td>{{$product->company->name}}</td>
                              <td><img src="{{$product->image_path}}" alt="" style="width: 90px;" class="img-thumbnail"></td>
                               <td>{{$product->formatted_price}}</td>
                               <td>{{$product->formatted_price}}</td>
                               <td>{{$product->bottle_count}}</td>

                               <td>
                                       <a href="{{route('dashboard.products.edit',$product->id)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i>@lang('site.edit')</a>
                                   <form method="post" class="delete" action="{{route('dashboard.products.destroy',$product->id)}}" style="display: inline-block">
                                       @csrf
                                       @method('delete')
                                       <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>@lang('site.delete')</button>
                                   </form>

                               </td>
                           </tr>

                           @endforeach
                           </tbody>

                       </table>
                       {{$products->appends(request()->query())->links()}}
                   @else
                       <h2>@lang('site.no_data_found')</h2>

                   @endif

               </div>
           </div>
        </section>
    </div>

@endsection
