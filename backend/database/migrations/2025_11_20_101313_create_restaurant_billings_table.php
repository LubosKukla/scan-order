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
        Schema::create('restaurant_billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->string('ico')->nullable();
            $table->string('dic')->nullable();
            $table->string('ic_dph')->nullable();
            $table->string('iban')->nullable();
            $table->date('trial_ends_at')->nullable();
            $table->string('subscription_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_billings');
    }
};
