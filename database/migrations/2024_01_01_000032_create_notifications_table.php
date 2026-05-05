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
            $table->uuid('id')->primary(); // UUID for Laravel's built-in notification system

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('type')
                  ->comment('Dot-notation event type, e.g. order.accepted, advisory.critical');

            $table->string('title');

            $table->text('message');

            $table->json('data')
                  ->nullable()
                  ->comment('JSON payload: links, IDs, action buttons, etc.');

            $table->timestamp('read_at')
                  ->nullable()
                  ->comment('NULL = unread');

            $table->timestamps();

            $table->index(['user_id', 'read_at']); // Fast unread count queries
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
