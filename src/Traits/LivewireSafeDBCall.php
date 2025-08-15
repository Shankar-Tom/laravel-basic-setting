<?php

namespace Shankar\LaravelBasicSetting\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

trait LivewireSafeDBCall
{
    public function safeCall(callable $callback)
    {
        if (method_exists($this, 'rules') || property_exists($this, 'rules')) {
            $this->validate();
        }
        try {
            DB::listen(function ($query) {
                Log::info($query->toRawSql());
            });

            return DB::transaction(fn() => $callback());
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $this->dispatch('livewire-error', [
                'message' => app()->isProduction()
                    ? "Something went wrong"
                    : $e->getMessage(),
            ]);

            return null;
        }
    }
}
