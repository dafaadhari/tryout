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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->longText('question_text');
            $table->text('opt_a');
            $table->text('opt_b');
            $table->text('opt_c');
            $table->text('opt_d');
            $table->text('opt_e');
            $table->string('correct_answer', 1)->nullable(); // A, B, C, D, atau E
            $table->longText('discussion_text')->nullable(); // Pembahasan
            $table->json('tkp_scores')->nullable(); // Bobot nilai TKP format JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
