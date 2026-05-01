<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * REQUIREMENT 4 (Bulk Order): Add bulk_discount_percent so farmers can
     * offer tiered discounts for large quantity orders.
     *
     * REQUIREMENT 5 (Negotiation): digital_agreement_id FK placeholder
     * so orders can link back to their generated agreement document.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Bulk discount offered by farmer (e.g. 5.00 = 5%)
            $table->decimal('bulk_discount_percent', 5, 2)
                  ->nullable()
                  ->default(null)
                  ->after('final_price')
                  ->comment('Percentage discount for bulk quantity orders');

            // Admin/system rejection reason for orders (future moderation)
            $table->text('admin_note')
                  ->nullable()
                  ->after('note')
                  ->comment('Admin note for disputes or moderation');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['bulk_discount_percent', 'admin_note']);
        });
    }
};
