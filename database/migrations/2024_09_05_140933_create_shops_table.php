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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('shop_id')->unique();
            $table->string('avatarUrl')->nullable();
            $table->string('name');
            $table->string('myshopifyDomain');
            $table->string('tags')->nullable();
            $table->string('notes')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->nullable();
            $table->string('title')->nullable();
            $table->string('image')->nullable();
            $table->foreignId("partner_id")->references("id")->on("partners")->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->references('id')->on('companies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
