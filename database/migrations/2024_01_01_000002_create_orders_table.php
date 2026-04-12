<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('crop_listing_id')->nullable()->constrained('crop_listings')->onDelete('set null');
            $table->string('crop_name');
            $table->decimal('quantity_kg', 10, 2);
            $table->decimal('proposed_price_per_kg', 10, 2); // buyer's initial offer
            $table->decimal('final_price_per_kg', 10, 2)->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->enum('status', [
                'pending',       // buyer submitted order request
                'negotiating',   // price negotiation in progress
                'confirmed',     // both agreed
                'agreement_signed',
                'payment_held',
                'delivered',
                'completed',
                'cancelled',
                'disputed'
            ])->default('pending');
            $table->text('delivery_address');
            $table->date('expected_delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
