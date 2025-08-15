<?php

namespace Shankar\LaravelBasicSetting\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InformMe
{
    public function handle(Request $request, Closure $next): Response
    {
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
