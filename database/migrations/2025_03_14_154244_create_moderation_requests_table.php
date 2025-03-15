<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('moderation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade'); // qui a reporté
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade'); // le post signalé
            $table->text('reason')->nullable(); // pourquoi ce post est signalé
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('moderator_feedback')->nullable(); // réponse de l'admin après traitement
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderation_requests');
    }
};
