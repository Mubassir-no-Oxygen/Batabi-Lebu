<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Farmer profile — linked 1:1 with users table.
     * verification_status controls access to the farmer dashboard.
     */
    public function up(): void
    {
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('farm_name');
            $table->string('district');
            $table->string('sub_district')->nullable();
            $table->decimal('land_size', 10, 2)->nullable();
            $table->enum('land_unit', ['acre', 'bigha', 'hectare'])->default('bigha');
            $table->text('crops_grown')->nullable(); // comma-separated or JSON description
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable(); // Admin fills this if rejected
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
