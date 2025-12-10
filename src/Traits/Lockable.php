<?php

namespace Shankar\LaravelBasicSetting\Traits;

use Illuminate\Support\Facades\Cache;

trait Lockable
{
    protected $currentLock = null;

    /**
     * Acquire a lock for this model.
     */
    public function acquireLock(int $seconds = 300): bool
    {
        $lockName = $this->getLockName();

        $this->currentLock = Cache::lock($lockName, $seconds);

        // Try to acquire lock
        return $this->currentLock->get();
    }

    // for checking if the lock exists or not 
    public function isLocked(): bool
    {
        $lockName = $this->getLockName();

        // Create a temporary lock object
        $lock = Cache::lock($lockName, 1);

        // Try to acquire the lock
        if ($lock->get()) {
            // If we can acquire it → lock was NOT held.
            $lock->release();
            return false;
        }

        // If we cannot acquire it → lock IS held by someone else.
        return true;
    }

    /**
     * Release the lock manually.
     */
    public function releaseLock(): void
    {
        if ($this->currentLock) {
            $this->currentLock->release();
        }
    }

    /**
     * Helper to generate unique model lock key.
     */
    protected function getLockName(): string
    {
        return sprintf('%s.%s.update.lock', $this->getTable(), $this->getKey());
    }
}
