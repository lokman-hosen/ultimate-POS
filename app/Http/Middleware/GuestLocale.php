<?php

namespace App\Http\Middleware;

use App;
use Closure;

/**
 * Locale for the guest pages (login, business registration, password reset).
 *
 * A language picked with ?lang= is remembered in the session, so it is kept
 * across the registration steps, validation errors and reloads. Without a
 * choice the guest default (Spanish unless GUEST_DEFAULT_LOCALE says otherwise) is used.
 */
class GuestLocale
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
        //Logged in users (e.g. password confirm) keep their own language
        if ($request->session()->has('user.language')) {
            App::setLocale($request->session()->get('user.language'));

            return $next($request);
        }

        $langs = config('constants.langs');

        $locale = $request->input('lang');
        if (! empty($locale) && array_key_exists($locale, $langs)) {
            $request->session()->put('guest_locale', $locale);
        } else {
            $locale = $request->session()->get('guest_locale', config('constants.guest_default_locale'));
        }

        if (empty($locale) || ! array_key_exists($locale, $langs)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
