<?php

namespace Shankar\LaravelBasicSetting\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

trait LivewireSafeDBCall
{
    public function safeCall(callable $callback)
    {
        try {
            return DB::transaction(fn() => $callback());
        } catch (Throwable $e) {
            if (app()->environment('production')) {
                $this->dispatch('livewire-error', [
                    'message' => "Something went wrong",
                ]);
            } else {
                $this->dispatch('livewire-error', [
                    'message' => $e->getMessage(),
                ]);
            }

            return null;
        }
    }
}
