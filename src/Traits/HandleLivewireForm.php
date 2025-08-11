<?php

namespace Shankar\LaravelBasicSetting\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait HandleLivewireForm
{
    public function submit($function)
    {

        try {
            DB::listen(function ($query) {
                Log::info($query->toRawSql());
            });
            DB::transaction(fn() => $this->$function());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            session()->flash('error', $th->getMessage());
            $this->dispatch('livewire-error', [
                'message' => app()->isProduction()
                    ? "Something went wrong"
                    : $th->getMessage(),
            ]);
        }
    }
}
