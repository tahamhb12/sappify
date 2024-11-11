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
        Schema::create('billing_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_id');
            $table->string('type');
            $table->string('amount');
            $table->string('currency');
            $table->string('billingOn')->nullable();
            $table->string('name');
            $table->boolean('isTest');
            $table->foreignId('app_id')->references('id')->on('shopify_apps')->cascadeOnDelete();
            $table->foreignId('shop_id')->references('id')->on('shops');
            $table->foreignId('partner_id')->references('id')->on('partners')->cascadeOnDelete();
            $table->string('occurred_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_events');
    }
};
