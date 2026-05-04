<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * REQUIREMENT 13: Weather alerts and farming advisories sent to farmers.
     * Admin/system publishes advisories; farmers in the target district receive them.
     *
     * target_district = NULL means broadcast to ALL farmers nationwide.
     * Severity levels control how urgently the alert is displayed.
     */
    public function up(): void
    {
        Schema::create('advisories', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('content');

            $table->enum('type', [
                'weather_alert',   // Cyclone, flood, drought warning
                'pest_warning',    // Locust, aphid, blight outbreak
                'farming_tip',     // Best practices, crop rotation advice
                'market_update',   // Price trend information
                'govt_notice',     // Government subsidies, schemes
                'other',
            ])->default('other');

            // NULL = broadcast to all districts (nationwide)
            $table->string('target_district')
                  ->nullable()
                  ->comment('Target a specific district; NULL = all farmers');

            $table->enum('severity', ['info', 'warning', 'critical'])
                  ->default('info')
                  ->comment('info = regular tip, warning = act soon, critical = immediate action');

            // Who published this advisory (must be admin)
            $table->foreignId('published_by')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->boolean('is_active')
                  ->default(true)
                  ->comment('Inactive advisories are hidden from farmers');

            // When this advisory stops being relevant
            $table->timestamp('expires_at')
                  ->nullable()
                  ->comment('Advisory auto-expires after this date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advisories');
    }
};
