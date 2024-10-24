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
                'RELATIONSHIP_DEACTIVATED',
                'RELATIONSHIP_INSTALLED',
                'RELATIONSHIP_REACTIVATED',
                'RELATIONSHIP_UNINSTALLED',
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
