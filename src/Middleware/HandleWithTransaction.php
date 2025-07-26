<?php

namespace Shankar\LaravelBasicSetting\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Shankar\LaravelBasicSetting\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class HandleWithTransaction
{
    use ApiResponse;
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('get')) {
            return $next($request); // skip DB transaction
        }

        DB::beginTransaction();

        $response = $next($request);
        if (!empty($response->exception)) {
            if ($response->exception instanceof \Illuminate\Validation\ValidationException) {
                if ($request->is('api/*')) {
                    $response = $this->validationErrorResponse(errors: Arr::first($response->exception->errors()));
                }
            } else {
                DB::rollBack();
                if ($request->is('api/*')) {
                    $exception = [
                        'action' => Route::currentRouteAction(),
                        'url' => Route::current()->uri,
                        'message' => $response->exception->getMessage(),
                        'inputs' => $request->all(),
                        'trace' => $response->exception->getTrace(),
                    ];
                    Log::error('Error :', $exception);
                    $response = $this->internalServerErrorResponse(exception: $exception);
                } else {
                    return back()->with('error', $response->exception->getMessage())->withInput();
                }
            }
        } else {
            DB::commit();
        }
        return $response;
    }
}
