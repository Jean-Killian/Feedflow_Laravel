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
    Schema::create('survey_questions', function (Blueprint $table) {
        $table->engine = 'InnoDB';

        $table->id();
        $table->foreignId('survey_id')->constrained()->onDelete('cascade');
        $table->string('question'); // titre de la question
        $table->enum('type', ['single_choice', 'multiple_choice', 'text', 'scale']);
        $table->json('data')->nullable(); // options JSON pour les choix multiples
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};