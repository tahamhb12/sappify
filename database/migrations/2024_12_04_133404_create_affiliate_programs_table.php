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
        Schema::create('affiliate_programs', function (Blueprint $table) {
            $table->id();
            $table->uuid('unique_id')->nullable();
            $table->string('app_url');
            $table->string('commission_rate');
            $table->string('amount_per_install');
            $table->string('min_payout');
            $table->string('sign_up_page')->nullable();
            $table->foreignId('app_id')->references('id')->on('shopify_apps')->cascadeOnDelete();
            $table->foreignId('partner_id')->references('id')->on('partners')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_programs');
    }
};
