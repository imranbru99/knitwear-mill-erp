<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('department_id')->nullable()->index();
            $table->unsignedBigInteger('buyer_id')->nullable()->index();
            $table->unsignedBigInteger('supplier_id')->nullable()->index();
            $table->unsignedBigInteger('employee_id')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('locale', 5)->default('en');
            $table->boolean('sees_all_branches')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('app_authentication_secret')->nullable();
            $table->text('app_authentication_recovery_codes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'branch_id', 'department_id', 'buyer_id', 'supplier_id', 'employee_id',
                'phone', 'locale', 'sees_all_branches', 'is_active',
                'app_authentication_secret', 'app_authentication_recovery_codes',
            ]);
        });
    }
};
