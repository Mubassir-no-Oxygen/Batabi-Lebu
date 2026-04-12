<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->string('agreement_number')->unique(); // e.g. AGR-2024-0001
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->string('crop_name');
            $table->decimal('quantity_kg', 10, 2);
            $table->decimal('price_per_kg', 10, 2);
            $table->decimal('total_amount', 12, 2);
            $table->text('delivery_address');
            $table->date('expected_delivery_date');
            $table->text('terms_and_conditions')->nullable();
            $table->enum('farmer_signed', ['pending', 'signed'])->default('pending');
            $table->enum('buyer_signed', ['pending', 'signed'])->default('pending');
            $table->timestamp('farmer_signed_at')->nullable();
            $table->timestamp('buyer_signed_at')->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agreements');
    }
};
