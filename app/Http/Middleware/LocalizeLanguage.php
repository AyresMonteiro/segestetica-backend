<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocalizeLanguage
{
    const languageHeader = 'messages-language';

    const availableLanguages = [
        'en',
        'pt-BR'
    ];

    public static function handleHeaderLocale(Request $request)
    {
        $headerLocale = $request->headers->get(self::languageHeader);

        if (!in_array($headerLocale, self::availableLanguages, true)) {
            return self::availableLanguages[0];
        }

        return $headerLocale;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = 'en';

        if ($request->hasHeader(self::languageHeader)) {
            $locale = self::handleHeaderLocale($request);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
