<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * FUTURE FEATURE: Delivery tracking with delivery partner assignment.
     */
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('delivery_partner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('pickup_address')->nullable();
            $table->text('delivery_address')->nullable();
            $table->enum('status', ['pending', 'picked_up', 'in_transit', 'delivered', 'returned'])
                  ->default('pending');
            $table->string('tracking_number')->nullable()->unique();
            $table->timestamp('expected_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
