<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * CROSS-CUTTING CONCERN: In-app notifications.
     * Used by multiple features:
     *   - Req 7:  Delivery partner assigned notification
     *   - Req 8:  Agreement ready to sign notification
     *   - Req 9:  Payment released/held notification
     *   - Req 11: Emergency request status updates
     *   - Req 13: Weather alert push to farmer
     *   - Req 14: Complaint status update
     *
     * type = a dot-notation event string (e.g. 'order.accepted', 'advisory.critical')
     * data = JSON payload for the frontend to render the notification.
     * read_at = NULL means unread.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
