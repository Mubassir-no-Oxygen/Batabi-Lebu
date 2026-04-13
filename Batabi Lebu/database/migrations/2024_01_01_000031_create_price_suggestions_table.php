<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * REQUIREMENT 6: Fair market price suggestions based on demand and season.
     * Admin or a system process populates these records.
     * When a farmer lists a crop, the system looks up this table to show
     * a suggested price range for that crop in the current season/district.
     *
     * Season codes (Bangladesh agricultural calendar):
     *   rabi   = Nov–Feb (winter)
     *   kharif = Mar–Oct (summer/monsoon)
     *   all    = year-round crop
     */
    public function up(): void
    {
        Schema::create('price_suggestions', function (Blueprint $table) {
            $table->id();

            // Match on crop name + category for lookup
            $table->string('crop_name');
            $table->enum('category', ['vegetable', 'fruit', 'grain', 'spice', 'other'])
                  ->default('other');

            // Seasonal price ranges (BDT per kg by default)
            $table->enum('season', ['rabi', 'kharif', 'all'])
                  ->default('all')
                  ->comment('Season this price suggestion applies to');

            // NULL = national average; set district for localised suggestions
            $table->string('district')
                  ->nullable()
                  ->comment('District-specific price; NULL = nationwide average');

            $table->decimal('suggested_min_price', 10, 2)
                  ->comment('Minimum fair price per unit (BDT)');

            $table->decimal('suggested_max_price', 10, 2)
                  ->comment('Maximum fair price per unit (BDT)');

            $table->string('unit')
                  ->default('kg')
                  ->comment('Price unit (kg, ton, etc.)');

            $table->enum('demand_level', ['low', 'medium', 'high'])
                  ->default('medium')
                  ->comment('Current market demand level for this crop');

            $table->enum('source', ['admin', 'govt_data', 'market_survey', 'system'])
                  ->default('admin')
                  ->comment('Where this price data was sourced from');

            $table->text('notes')->nullable();

            $table->boolean('is_active')->default(true);

            // Validity window for this price range
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();

            // Who entered/updated this record
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();

            // Composite index for fast lookup during crop listing
            $table->index(['crop_name', 'category', 'season', 'district']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_suggestions');
    }
};
