<?php

namespace Shankar\LaravelBasicSetting\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\Catch_;

trait HandleLivewireForm
{
    public function submit($function)
    {
        if (method_exists($this, 'rules') || property_exists($this, 'rules')) {
            $this->validate();
        }

        try {
            DB::listen(function ($query) {
                Log::info($query->toRawSql());
            });
            DB::transaction(fn() => $this->$function());
        } catch (ValidationException $e) {
            throw $e;
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
