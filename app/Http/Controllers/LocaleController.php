<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocaleController extends Controller
{
    public function update(Request $request, string $locale)
    {
        $supported = config('app.supported_locales', ['en', 'fr', 'ar']);

        if (in_array($locale, $supported, true)) {
            session(['locale' => $locale]);
            App::setLocale($locale);
        }

        return back();
    }
}