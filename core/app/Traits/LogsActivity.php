<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            self::logEvent($model, 'created', 'Yeni kayıt oluşturuldu.');
        });

        static::updated(function (Model $model) {
            $changes = $model->getChanges();

            // Filter out sensitive and timestamp fields
            $filteredChanges = array_diff_key($changes, array_flip([
                'password', 'remember_token', 'updated_at', 'created_at', 'email_verified_at',
            ]));

            if (empty($filteredChanges)) {
                return;
            }

            $details = [];
            foreach ($filteredChanges as $key => $newValue) {
                $oldValue = $model->getOriginal($key);
                $details[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }

            self::logEvent($model, 'updated', 'Kayıt güncellendi.', $details);
        });

        static::deleted(function (Model $model) {
            self::logEvent($model, 'deleted', 'Kayıt silindi.');
        });
    }

    protected static function logEvent(Model $model, string $event, string $description, array $details = [])
    {
        $actionType = self::mapEventToActionType($model, $event);

        ActivityLog::logActivity(
            $actionType,
            $description,
            array_merge([
                'model' => class_basename($model),
                'id' => $model->getKey(),
            ], $details)
        );
    }

    protected static function mapEventToActionType(Model $model, string $event): string
    {
        $baseName = strtolower(class_basename($model));

        // Define mapping for specific models if needed
        $mappings = [
            'reservation' => 'reservation',
            'business' => 'business',
            'user' => 'auth',
            'setting' => 'system',
            'businessapplication' => 'business',
        ];

        $category = $mappings[$baseName] ?? 'system';

        return "{$category}_{$event}";
    }
}
