<?php

// app/Http/Middleware/Language.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;

class Language
{
    public function handle($request, Closure $next)
    {
    	if(strcmp(Route::current()->uri,"/")!=0 AND strcmp(explode(Route::current()->uri,"/")[0],"pages")!=0){
        if ($request->session()->has('applocale') AND array_key_exists(session('applocale'), Config::get('languages'))) {
            App::setLocale(session('applocale'));
        }else {
            App::setLocale(Config::get('app.fallback_locale'));
        }
    	}
        return $next($request);
    }
}