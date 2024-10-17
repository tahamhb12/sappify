<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shopify_app_events', function (Blueprint $table) {
            $table->id();
            $table->string('occurred_at');
            $table->enum('type', [
                'CREDIT_APPLIED',
                'CREDIT_FAILED',
                'CREDIT_PENDING',
                'ONE_TIME_CHARGE_ACCEPTED',
                'ONE_TIME_CHARGE_ACTIVATED',
                'ONE_TIME_CHARGE_DECLINED',
                'ONE_TIME_CHARGE_EXPIRED',
                'RELATIONSHIP_DEACTIVATED',
                'RELATIONSHIP_INSTALLED',
                'RELATIONSHIP_REACTIVATED',
                'RELATIONSHIP_UNINSTALLED',
                'SUBSCRIPTION_APPROACHING_CAPPED_AMOUNT',
                'SUBSCRIPTION_CAPPED_AMOUNT_UPDATED',
                'SUBSCRIPTION_CHARGE_ACCEPTED',
                'SUBSCRIPTION_CHARGE_ACTIVATED',
                'SUBSCRIPTION_CHARGE_CANCELED',
                'SUBSCRIPTION_CHARGE_DECLINED',
                'SUBSCRIPTION_CHARGE_EXPIRED',
                'SUBSCRIPTION_CHARGE_FROZEN',
                'SUBSCRIPTION_CHARGE_UNFROZEN',
                'USAGE_CHARGE_APPLIED'
            ]);
            $table->foreignId('app_id')->references('id')->on('shopify_apps');
            $table->foreignId('shop_id')->references('id')->on('shops');
            $table->foreignId("partner_id")->references("id")->on("partners");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopify_app_events');
    }
};
