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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->string("amount");
            $table->string("paypal_email");
            $table->enum("status",["pending","approved","rejected"])->default("pending");
            $table->string("comment")->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('affiliate_program_id')->constrained("affiliate_programs")->cascadeOnDelete();
            $table->foreignId('partner_id')->nullable()->references('id')->on('partners')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
