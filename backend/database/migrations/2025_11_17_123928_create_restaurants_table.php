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
            $table->string('ico')->nullable();
            $table->string('dic')->nullable();
            $table->string('dic_dph')->nullable();
            $table->string('iban')->nullable();
            $table->boolean('is_active')->nullable();
            $table->string('name')->nullable();
            $table->string('name_boss')->nullable();
            $table->string('type_of_restaurant')->nullable();
            $table->string('description')->nullable();
            $table->string('logo_path')->nullable();
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
