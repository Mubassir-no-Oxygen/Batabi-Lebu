<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * REQUIREMENT 8: Digital agreements generated automatically after both
     * parties confirm the price and quantity of an order.
     *
     * The agreement is immutable once signed by both parties.
     * document_path stores a generated PDF path (future implementation).
     */
    public function up(): void
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();

            // One agreement per confirmed order
            $table->foreignId('order_id')
                  ->unique()
                  ->constrained('orders')
                  ->onDelete('cascade');

            $table->foreignId('farmer_id')
                  ->constrained('farmers')
                  ->onDelete('cascade');

            $table->foreignId('buyer_id')
                  ->constrained('buyers')
                  ->onDelete('cascade');

            // Core agreement terms
            $table->decimal('agreed_quantity', 10, 2)
                  ->comment('Final agreed quantity from order/negotiation');

            $table->string('quantity_unit')
                  ->comment('Unit of the agreed quantity (kg, ton, etc.)');

            $table->decimal('agreed_price_per_unit', 10, 2)
                  ->comment('Final agreed price per unit in BDT');

            $table->decimal('total_amount', 12, 2)
                  ->comment('agreed_quantity × agreed_price_per_unit');

            $table->decimal('bulk_discount_percent', 5, 2)
                  ->default(0)
                  ->comment('Discount applied for bulk orders (0 = none)');

            $table->text('terms')
                  ->nullable()
                  ->comment('Custom terms, delivery conditions, etc.');

            // Signing workflow
            $table->enum('status', ['draft', 'pending_farmer', 'pending_buyer', 'signed', 'cancelled'])
                  ->default('draft')
                  ->comment('Track signing progress of both parties');

            $table->timestamp('farmer_signed_at')->nullable();
            $table->timestamp('buyer_signed_at')->nullable();

            // Generated agreement document (PDF path in storage)
            $table->string('document_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agreements');
    }
};
