<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $availableLocales = ['pt', 'en', 'es', 'fr'];

        $routeLocale = $request->route('locale');
        $sessionLocale = $request->session()->get('locale', config('app.locale', 'pt'));

        $locale = $routeLocale ?: $sessionLocale;

        if (! in_array($locale, $availableLocales, true)) {
            $locale = 'pt';
        }

        App::setLocale($locale);
        $request->session()->put('locale', $locale);
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
