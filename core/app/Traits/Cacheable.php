<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    /**
     * Boot the trait.
     * Registers model events to clear cache.
     */
    protected static function bootCacheable()
    {
        static::saved(function ($model) {
            $model->clearModelCache();
        });

        static::deleted(function ($model) {
            $model->clearModelCache();
        });
    }

    /**
     * Clear the cache related to this model.
     */
    public function clearModelCache()
    {
        $tags = $this->getCacheTags();
        if (! empty($tags)) {
            try {
                Cache::tags($tags)->flush();
            } catch (\BadMethodCallException $e) {
                // Fallback for drivers that don't support tagging
                $this->clearManualKeys();
            }
        } else {
            $this->clearManualKeys();
        }
    }

    /**
     * Clear manual keys if tagging is not supported.
     */
    protected function clearManualKeys()
    {
        foreach ($this->getCacheKeysToClear() as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Tags for this model cache.
     */
    protected function getCacheTags(): array
    {
        return [strtolower(class_basename($this))];
    }

    /**
     * Manual keys to clear if tags are not supported.
     */
    protected function getCacheKeysToClear(): array
    {
        return [];
    }
}
