<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $enabledLanguages = config('app.supported_locales');
        $lang = $request->route('lang');
        if (!in_array($lang, $enabledLanguages)) {
            $lang = 'en';
        }
        app()->setLocale($lang);
        $request->route()->forgetParameter('lang');
        return $next($request);
    }
}
