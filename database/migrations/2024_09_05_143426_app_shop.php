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
        Schema::create('app_shop', function (Blueprint $table) {
            $table->id();
            $table->string('app_id');
            $table->foreign('app_id')->references('app_id')->on('shopify_apps');
            $table->string('shop_id');
            $table->foreign('shop_id')->references('shop_id')->on('shops');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::dropIfExists('app_shop');

    }
};
