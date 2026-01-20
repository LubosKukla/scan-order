<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_billings', function (Blueprint $table) {
            $table->boolean('bill_to_company')->default(false)->after('company_name');
            $table->string('billing_street')->nullable()->after('iban');
            $table->string('billing_city')->nullable()->after('billing_street');
            $table->string('billing_zip')->nullable()->after('billing_city');
            $table->string('billing_country')->nullable()->after('billing_zip');
            $table->string('billing_email')->nullable()->after('billing_country');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_billings', function (Blueprint $table) {
            $table->dropColumn([
                'bill_to_company',
                'billing_street',
                'billing_city',
                'billing_zip',
                'billing_country',
                'billing_email',
            ]);
        });
    }
};
