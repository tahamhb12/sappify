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
        Schema::create('shopify_apps', function (Blueprint $table) {
            $table->string('app_id')->primary();
            $table->string('name');
            $table->string('api_key');
            $table->string('partner_id');
            $table->foreign('partner_id')->references('partner_id')->on('partners');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopify_apps');
    }
};
