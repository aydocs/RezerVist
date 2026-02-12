<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendReengagementCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reengagement-coupons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends re-engagement coupons to inactive customers (30+ days).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Finding inactive customers...');

        $inactiveUsers = \App\Models\User::where('role', 'user')
            ->where(function ($query) {
                $query->where('last_reservation_at', '<', now()->subDays(30))
                    ->orWhereNull('last_reservation_at');
            })
            ->get();

        $this->info('Found ' . $inactiveUsers->count() . ' inactive users.');

        foreach ($inactiveUsers as $user) {
            // Check if we already sent a re-engagement coupon recently (e.g. in last 7 days)
            // This prevents spamming. For now, let's keep it simple.

            // Generate a unique re-engagement code
            $code = 'WELCBACK' . strtoupper(substr(uniqid(), -4));

            $coupon = \App\Models\Coupon::create([
                'code' => $code,
                'type' => 'percentage',
                'value' => 15, // 15% discount
                'min_amount' => 100,
                'max_uses' => 1,
                'expires_at' => now()->addDays(7), // Expires in a week
                'is_active' => true,
            ]);

            // Notify User
            $user->notify(new \App\Notifications\ReengagementNotification($coupon));
            
            $this->line('Sent coupon ' . $code . ' to user: ' . $user->email);
        }

        $this->info('Smart CRM re-engagement process completed.');
    }
}
