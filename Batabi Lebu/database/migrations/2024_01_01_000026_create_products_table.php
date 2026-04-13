<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * FUTURE FEATURE: Supplier marketplace for seeds, fertilizers, etc.
     * Suppliers are users with role='supplier'.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade');
            $table->string('product_name');
            $table->string('category')->nullable(); // e.g. seed, fertilizer, pesticide
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('unit')->nullable(); // e.g. kg, liter, packet
            $table->integer('stock_quantity')->default(0);
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
