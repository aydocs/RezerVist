<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('support_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_message_id')->constrained('contact_messages')->onDelete('cascade');
            $table->text('message');
            $table->boolean('is_admin')->default(false); // true = admin sent, false = user sent
            $table->boolean('is_read')->default(false);
            $table->string('attachment')->nullable();
            $table->timestamps();
        });

        // Migrate existing data
        $messages = \DB::table('contact_messages')->get();
        foreach ($messages as $msg) {
            // 1. Create a reply entry for the original user message
            \DB::table('support_replies')->insert([
                'contact_message_id' => $msg->id,
                'message' => $msg->message,
                'is_admin' => false,
                'is_read' => true, // Original message matches opened state
                'created_at' => $msg->created_at,
                'updated_at' => $msg->created_at,
            ]);

            // 2. Create a reply entry for the admin reply if exists
            if (!empty($msg->reply)) {
                \DB::table('support_replies')->insert([
                    'contact_message_id' => $msg->id,
                    'message' => $msg->reply,
                    'is_admin' => true,
                    'is_read' => $msg->is_read, // Typically verified by user checking it
                    'created_at' => $msg->replied_at ?? now(),
                    'updated_at' => $msg->replied_at ?? now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_replies');
    }
};
