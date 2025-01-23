<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class SetLocale
{

    public function handle($request, Closure $next)
    {

        if ( Session::has('locale'))
            App::setLocale( Session::get('locale') );
        elseif ( $request->header('Accept-Language') )
            App::setLocale( substr( $request->header('Accept-Language') , 0 , 2) );
        else
            App::setLocale( Config::get('app.locale') );

        return $next($request);
    }
}
