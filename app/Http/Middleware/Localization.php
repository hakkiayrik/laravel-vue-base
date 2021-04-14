<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if($request->has('lang')) {
    		session()->put('locale', $request->input('lang'));
		} else if($request->hasHeader('Language')) {
    		session()->put('locale', $request->header('Language'));
		} else if(!session()->has('locale')) {
    		session()->put('locale', env('MIX_DEFAULT_LANGUAGE'));
		}

    	App::setLocale(session()->get('locale'));

        return $next($request);
    }
}
