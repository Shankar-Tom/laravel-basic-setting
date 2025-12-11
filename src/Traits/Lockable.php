<?php

namespace Shankar\LaravelBasicSetting\Traits;

use Illuminate\Support\Facades\Cache;

trait Lockable
{
    protected $currentLock = null;

    /**
     * Acquire a lock for this model.
     */
    public function acquireLock(int $seconds = 600): bool
    {
        $lockName = $this->getLockName();
        if (!Cache::has($lockName)) {
            Cache::put($lockName, true, $seconds);
            return true;
        }
        return false;
    }

    // for checking if the lock exists or not 
    public function isLocked(): bool
    {
        return Cache::has($this->getLockName());
    }

    /**
     * Release the lock manually.
     */
    public function releaseLock(): void
    {
        Cache::forget($this->getLockName());
    }

    /**
     * Helper to generate unique model lock key.
     */
    protected function getLockName(): string
    {
        return sprintf('%s.%s.update.lock', $this->getTable(), $this->getKey());
    }
}
