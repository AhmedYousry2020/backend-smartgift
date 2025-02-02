<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ auth('admin')->user()->image_path }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p> {{auth('admin')->user()->first_name }} {{auth('admin')->user()->last_name}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">

            <li> <a href="{{route('dashboard.index')}}"><i class="fa fa-th"></i><span>@lang('site.dashboard')</span></a></li>

            @if (auth('admin')->user())
            <li> <a href="{{route('dashboard.categories.index')}}"><i class="fa fa-cubes"></i><span>@lang('site.categories')</span></a></li>
            @endif
            @if (auth('admin')->user())
            <li> <a href="{{route('dashboard.companies.index')}}"><i class="fa fa-cubes"></i><span>@lang('site.companies')</span></a></li>
            @endif

            @if (auth('admin')->user())
            <li> <a href="{{route('dashboard.mosques.index')}}"><i class="fa fa-cubes"></i><span>@lang('site.mosques')</span></a></li>
            @endif

            @if (auth('admin')->user())
            <li> <a href="{{route('dashboard.products.index')}}"><i class="fa fa-product-hunt"></i><span>@lang('site.products')</span></a></li>
            @endif
            @if (auth('admin')->user())
            <li> <a href="{{route('dashboard.users.index')}}"><i class="fa fa-users"></i><span>@lang('site.users')</span></a></li>
            @endif
            @if (auth('admin')->user())
            <li> <a href="{{route('dashboard.clients.index')}}"><i class="fa fa-user"></i><span>@lang('site.clients')</span></a></li>
            @endif
            @if (auth('admin')->user())
            <li> <a href="{{route('dashboard.orders.index')}}"><i class="fa fa-first-order"></i><span>@lang('site.orders')</span></a></li>
            @endif
        </ul>


    </section>

</aside>
