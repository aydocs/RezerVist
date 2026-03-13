<?php

namespace App\Traits;

use App\Models\Business;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

trait HasBusinessScope
{
    /**
     * Boot the trait to apply a global scope for business_id.
     */
    protected static function bootHasBusinessScope()
    {
        static::addGlobalScope('business_iso_isolation', function (Builder $builder) {
            $businessId = static::getBusinessIdForScope();

            if ($businessId) {
                $builder->where(static::getBusinessIdColumn(), $businessId);
            }
        });

        static::creating(function ($model) {
            if (!$model->{static::getBusinessIdColumn()}) {
                $model->{static::getBusinessIdColumn()} = static::getBusinessIdForScope();
            }
        });
    }

    /**
     * Get the business ID to filter by.
     * Can be customized per model if needed.
     */
    protected static function getBusinessIdForScope()
    {
        // 1. Check current logged in staff business
        if (auth('staff')->check()) {
            return auth('staff')->user()->business_id;
        }

        // 2. Check current session (for QR/Customer context)
        if (Session::has('active_business_id')) {
            return Session::get('active_business_id');
        }

        // 3. Fallback to owner if they are logged in via web/admin
        if (auth('web')->check() && auth('web')->user()->business_id) {
            return auth('web')->user()->business_id;
        }

        return null;
    }

    /**
     * Get the name of the business ID column.
     */
    protected static function getBusinessIdColumn(): string
    {
        return 'business_id';
    }
}
