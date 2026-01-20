<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('other_restaurant_type')->nullable()->after('type_restaurant_id');
            $table->string('other_type_kitchen')->nullable()->after('other_restaurant_type');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['other_restaurant_type', 'other_type_kitchen']);
        });
    }
};
