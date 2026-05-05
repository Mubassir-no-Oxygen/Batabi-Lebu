<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Crop listings by farmers — buyers can browse and order.
     */
    public function up(): void
    {
        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('farmers')->onDelete('cascade');
            $table->string('crop_name');
            $table->enum('category', ['vegetable', 'fruit', 'grain', 'spice', 'other'])->default('other');
            $table->decimal('quantity', 10, 2); // Available quantity
            $table->enum('unit', ['kg', 'ton', 'quintal', 'maund'])->default('kg');
            $table->decimal('price_per_unit', 10, 2); // Price per unit in BDT
            $table->date('harvest_date')->nullable();
            $table->date('available_from')->nullable();
            $table->date('available_until')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // stored in storage/app/public/crops
            $table->enum('status', ['available', 'sold_out', 'upcoming'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crops');
    }
};
