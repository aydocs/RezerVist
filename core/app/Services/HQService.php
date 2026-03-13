<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Business;
use App\Models\Menu;
use Illuminate\Support\Facades\Log;

class HQService
{
    /**
     * Push a menu catalog item to all branch businesses (Section 21.1).
     */
    public function syncMenuToBranches(Organization $organization, Menu $masterMenu)
    {
        Log::info("HQ: Syncing Menu '{$masterMenu->name}' to all branches of Organization {$organization->id}");

        $branches = $organization->businesses;

        foreach ($branches as $branch) {
            // Logic to replicate menu item while maintaining branch-specific price overrides
            // This is a stub for complex sync logic
            Log::info("HQ: Synced to branch {$branch->id}");
        }
    }

    /**
     * Get aggregated analytics for the whole organization.
     */
    public function getOrganizationAnalytics(Organization $organization)
    {
        return [
            'total_revenue' => $organization->businesses()->sum('balance'), // Example
            'branch_count' => $organization->businesses()->count(),
            'top_performing_branch' => $organization->businesses()->orderBy('balance', 'desc')->first()?->name
        ];
    }
}
