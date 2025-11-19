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
        Schema::create('restaurant_type_kitchen', function (Blueprint $table) {
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');

            $table->foreignId('type_kitchen_id')->constrained('type_kitchens')->onDelete('cascade');

            $table->primary(['restaurant_id', 'type_kitchen_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_type_kitchen');
    }
};
