<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * REQUIREMENT 10: Users can report fraud, scams, or suspicious activities.
     * This is separate from general complaints (complaints table).
     * Fraud reports are higher severity and tracked by the admin team.
     *
     * Reporter = the user who files the report.
     * Reported user = the user being reported.
     * order_id is optional — fraud may happen outside of a direct order.
     */
    public function up(): void
    {
        Schema::create('fraud_reports', function (Blueprint $table) {
            $table->id();

            // Who filed the report
            $table->foreignId('reporter_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('User who submitted this fraud report');

            // Who is being reported
            $table->foreignId('reported_user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('User accused of fraud or suspicious activity');

            // Optional: link to the relevant order
            $table->foreignId('order_id')
                  ->nullable()
                  ->constrained('orders')
                  ->onDelete('set null')
                  ->comment('The order involved in the fraud, if applicable');

            $table->enum('type', [
                'fake_listing',     // Crop listed does not exist
                'payment_fraud',    // False payment claim
                'non_delivery',     // Goods paid for but not delivered
                'quality_fraud',    // Product quality very different from listing
                'impersonation',    // Fake identity
                'scam',             // General scam attempt
                'other',
            ])->default('other');

            $table->text('description')
                  ->comment('Detailed description of the fraudulent activity');

            $table->string('evidence_path')
                  ->nullable()
                  ->comment('Path to uploaded photo/document evidence');

            $table->enum('status', ['pending', 'investigating', 'resolved', 'dismissed'])
                  ->default('pending');

            $table->text('admin_note')
                  ->nullable()
                  ->comment('Admin investigation notes and resolution details');

            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fraud_reports');
    }
};
