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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('type_restaurant_id')->nullable()->constrained('type_restaurants')->nullOnDelete();
            $table->boolean('is_active')->nullable();
            $table->string('name')->nullable();
            $table->string('name_boss')->nullable();
            $table->string('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->integer('number_of_tables')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
