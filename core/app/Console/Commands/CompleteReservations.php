<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CompleteReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically marks past reservations as completed and awards loyalty points.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pastReservations = \App\Models\Reservation::where('status', 'approved')
            ->where('end_time', '<', now())
            ->get();

        $this->info('Found '.$pastReservations->count().' reservations to complete.');

        foreach ($pastReservations as $reservation) {
            $user = $reservation->user;
            $hadCompletedReservations = \App\Models\Reservation::where('user_id', $user->id)
                ->where('status', 'completed')
                ->exists();

            $reservation->update(['status' => 'completed']);

            // Award points: 1 point per 10 TL
            $points = floor($reservation->total_amount / 10);
            if ($points > 0) {
                $user->rewardPoints($points);
            }

            // Referral Reward Logic
            if (! $hadCompletedReservations && $user->referred_by_id) {
                $referrer = $user->referrer;
                if ($referrer) {
                    $rewardAmount = 50.00; // 50 TL Reward
                    $referrer->increment('balance', $rewardAmount);

                    // Create activity log or notification for referrer
                    \App\Models\ActivityLog::logActivity(
                        'referral_reward',
                        $user->name.' isimli arkadaşınız ilk rezervasyonunu tamamladığı için 50 TL kazandınız!',
                        ['referred_user_id' => $user->id, 'reward' => $rewardAmount],
                        $referrer->id
                    );

                    // Add Wallet Transaction Record
                    \App\Models\WalletTransaction::create([
                        'user_id' => $referrer->id,
                        'amount' => $rewardAmount,
                        'type' => 'topup', // Considered a topup/reward
                        'status' => 'success',
                        'description' => 'Arkadaş Davet Ödülü ('.$user->name.')',
                        'reference_id' => $user->id,
                    ]);

                    $referrer->notify(new \App\Notifications\OnboardingNotification(
                        'Tebrikler! Davet ettiğiniz '.$user->name.' ilk rezervasyonunu tamamladı ve hesabınıza 50 TL tanımlandı.',
                        route('search.index')
                    ));
                }
            }

            $this->comment('Completed reservation #'.$reservation->id.' | Points awarded: '.$points);
        }

        $this->info('Reservation completion process finished.');
    }
}
