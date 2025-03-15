<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('content')->nullable();         // texte optionnel
            $table->string('media_path')->nullable();    // image ou vidéo optionnelle
            $table->enum('media_type', ['image', 'video', 'link', 'none'])->default('none'); // type de contenu
            $table->string('shared_link')->nullable();   // lien partagé si besoin
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
