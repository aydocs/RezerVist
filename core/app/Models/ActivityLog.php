<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action_type',
        'description',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method to log activity
    public static function logActivity($actionType, $description, $metadata = null, $userId = null)
    {
        try {
            return self::create([
                'user_id' => $userId ?? (auth()->check() ? auth()->id() : null),
                'action_type' => $actionType,
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'metadata' => $metadata,
            ]);
        } catch (\Exception $e) {
            // Silently fail if logging fails (e.g. foreign key constraint with stale session)
            return null;
        }
    }

    // Specific action loggers
    public static function logLogin($userId)
    {
        return self::logActivity('login', 'Kullanıcı sisteme giriş yaptı', null, $userId);
    }

    public static function logLogout($userId)
    {
        return self::logActivity('logout', 'Kullanıcı sistemden çıkış yaptı', null, $userId);
    }

    public static function logFailedLogin($email)
    {
        return self::logActivity('failed_login', "Başarısız giriş denemesi: {$email}", ['email' => $email], null);
    }

    public static function logUserCreated($user)
    {
        return self::logActivity('user_created', "{$user->name} adlı kullanıcı oluşturuldu ({$user->role})", [
            'user_id' => $user->id,
            'role' => $user->role,
        ]);
    }

    public static function logUserUpdated($user, $changes)
    {
        return self::logActivity('user_updated', "{$user->name} kullanıcı bilgileri güncellendi", [
            'user_id' => $user->id,
            'changes' => $changes,
        ], auth()->id());
    }

    public static function logReservation($reservation, $action = 'created')
    {
        $message = match ($action) {
            'created' => "Yeni rezervasyon oluşturuldu: #{$reservation->id}",
            'updated' => "Rezervasyon güncellendi: #{$reservation->id}",
            'cancelled' => "Rezervasyon iptal edildi: #{$reservation->id}",
            'confirmed' => "Rezervasyon onaylandı: #{$reservation->id}",
            'completed' => "Rezervasyon tamamlandı: #{$reservation->id}",
            default => "Rezervasyon işlemi: {$action} (#{$reservation->id})"
        };

        // Send Notification for Creation and Updates (others handled by Controllers usually)
        if (in_array($action, ['created', 'updated']) && $reservation->user) {
            try {
                // Determine status string for the notification class
                $notifStatus = $reservation->status;
                if ($action === 'created') {
                    $notifStatus = 'created';
                } // Custom status for 'created' context
                if ($action === 'updated') {
                    $notifStatus = 'updated';
                }

                // We might need to ensure ReservationStatusNotification handles 'created'/'updated' or we pass a custom message.
                // Using SystemNotification for generic updates to be safe and specific
                $title = 'Rezervasyon '.($action === 'created' ? 'Oluşturuldu' : 'Güncellendi');
                $userMsg = 'Rezervasyonunuz başarıyla '.($action === 'created' ? 'oluşturuldu' : 'güncellendi').'.';

                $reservation->user->notify(new \App\Notifications\SystemNotification(
                    $title,
                    $userMsg,
                    'calendar',
                    'reservation',
                    route('profile.reservations')
                ));
            } catch (\Exception $e) {
                // silent fail for logs
            }
        }

        return self::logActivity("reservation_{$action}", $message, [
            'reservation_id' => $reservation->id,
            'business_name' => $reservation->business->name ?? 'Bilinmeyen İşletme',
            'user' => $reservation->user->name ?? 'Bilinmeyen',
            'status' => $reservation->status,
            'price' => $reservation->price,
            'guest_count' => $reservation->guest_count,
            'start_time' => $reservation->start_time,
            'end_time' => $reservation->end_time,
            'full_data' => $reservation->toArray(),
        ]);
    }

    public static function logBusinessUpdate($business, $description)
    {
        return self::logActivity('business_update', $description, [
            'business_id' => $business->id,
            'business_name' => $business->name,
        ]);
    }

    public static function logSettingChange($key, $oldValue, $newValue)
    {
        return self::logActivity('setting_change', "Sistem ayarı değiştirildi: {$key}", [
            'key' => $key,
            'old_value' => $oldValue,
            'new_value' => $newValue,
        ]);
    }

    public static function logSystemAlert($type, $message, $metadata = [])
    {
        return self::logActivity('system_alert', $message, array_merge(['alert_type' => $type], $metadata));
    }

    public static function logPayment($payment, $status = 'success')
    {
        $message = match ($status) {
            'success' => "Ödeme başarılı: {$payment->amount} TL",
            'failed' => "Ödeme başarısız: {$payment->amount} TL",
            'refunded' => "Ödeme iade edildi: {$payment->amount} TL",
            default => "Ödeme işlemi: {$status}"
        };

        // Notification for Payment
        try {
            $user = \App\Models\User::find($payment->user_id);
            if ($user) {
                $title = $status === 'success' ? 'Ödeme Başarılı' : 'Ödeme Başarısız';
                $icon = $status === 'success' ? 'credit-card' : 'alert-circle';
                $user->notify(new \App\Notifications\SystemNotification(
                    $title,
                    $message,
                    $icon,
                    'payment',
                    route('profile.wallet.index')
                ));
            }
        } catch (\Exception $e) {
        }

        return self::logActivity("payment_{$status}", $message, [
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'user_id' => $payment->user_id,
            'reservation_id' => $payment->reservation_id ?? null,
        ], $payment->user_id);
    }

    public static function logWalletTransaction($transaction, $status = 'success')
    {
        $message = "Cüzdan işlemi ({$transaction->type}): {$transaction->amount} TL - {$status}";

        // Notification for Wallet
        try {
            $user = \App\Models\User::find($transaction->user_id);
            if ($user) {
                $typeLabel = $transaction->type === 'topup' ? 'Bakiye Yükleme' : 'Ödeme';
                $title = "Cüzdan İşlemi: {$typeLabel}";
                $notifMsg = "Hesabınızda {$transaction->amount} TL tutarında işlem gerçekleşti.";

                $user->notify(new \App\Notifications\SystemNotification(
                    $title,
                    $notifMsg,
                    'wallet',
                    'wallet',
                    route('profile.wallet.index')
                ));
            }
        } catch (\Exception $e) {
        }

        return self::logActivity("wallet_{$transaction->type}", $message, [
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amount,
            'user_id' => $transaction->user_id,
            'status' => $status,
            'reference_id' => $transaction->reference_id,
        ], $transaction->user_id);
    }
}
