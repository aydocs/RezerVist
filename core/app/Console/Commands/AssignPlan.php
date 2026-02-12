<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Models\Package;
use Illuminate\Console\Command;

class AssignPlan extends Command
{
    protected $signature = 'saas:assign-plan {business_id} {package_slug} {months=1}';

    protected $description = 'Assign a subscription plan to a business';

    public function handle()
    {
        $business = Business::find($this->argument('business_id'));
        if (! $business) {
            $this->error('Business not found!');

            return;
        }

        $package = Package::where('slug', $this->argument('package_slug'))->first();
        if (! $package) {
            $this->error("Package slug '{$this->argument('package_slug')}' not found!");

            return;
        }

        $months = (int) $this->argument('months');

        // Use the service for a 'perfect' logic
        $service = app(\App\Services\SubscriptionService::class);
        $subscription = $service->assignPlan($business, $package, $months, 'manual');

        $this->info("Successfully assigned '{$package->name}' to '{$business->name}' for {$months} months.");
    }
}
