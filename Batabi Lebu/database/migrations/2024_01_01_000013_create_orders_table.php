<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Order/trade requests from buyers to farmers.
     * final_price and offered_price support future negotiation module.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('buyers')->onDelete('cascade');
            $table->foreignId('crop_id')->constrained('crops')->onDelete('cascade');
            $table->decimal('requested_quantity', 10, 2);
            $table->decimal('offered_price', 10, 2)->nullable();  // Buyer's offer (optional)
            $table->decimal('final_price', 10, 2)->nullable();    // Set after negotiation/acceptance
            $table->text('note')->nullable();                      // Buyer notes to farmer
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed', 'cancelled'])
                  ->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
