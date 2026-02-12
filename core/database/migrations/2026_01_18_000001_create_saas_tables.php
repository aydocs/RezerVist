<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Packages Table (Starter, Pro, Enterprise)
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price_monthly', 10, 2);
            $table->decimal('price_yearly', 10, 2);
            $table->json('features')->nullable(); // e.g. { "pos": true, "table_limit": 100 }
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Subscriptions Table
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable(); // Null = Lifetime access
            $table->timestamp('trial_ends_at')->nullable();
            $table->enum('status', ['active', 'cancelled', 'expired', 'grace_period', 'trial'])->default('active');
            $table->string('payment_method')->nullable(); // 'manual', 'iyzico', 'transfer'
            $table->timestamps();
        });

        // 3. Update Businesses Table (Cache/Status fields)
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('subscription_status')->default('trial')->index(); // active, expired, trial
            $table->timestamp('subscription_ends_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['subscription_status', 'subscription_ends_at']);
        });
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('packages');
    }
};
