<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['farmer', 'buyer', 'admin'])->default('buyer')->after('email');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'district')) {
                $table->string('district')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('district');
            }
            if (!Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(array_filter(
                ['role', 'phone', 'district', 'address', 'is_verified'],
                fn($col) => Schema::hasColumn('users', $col)
            ));
        });
    }
};
