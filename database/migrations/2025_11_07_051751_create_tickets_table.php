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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('requester_email');
            $table->string('requester_name')->nullable();
            $table->string('status')->default('open');
            $table->string('priority')->default('normal');
            $table->string('tier')->nullable();
            $table->string('customer_status')->nullable();
            $table->timestamp('last_public_reply_at')->nullable();
            $table->timestamp('last_customer_reply_at')->nullable();
            $table->string('assigned_to')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('tier');
            $table->index(['status', 'last_public_reply_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
