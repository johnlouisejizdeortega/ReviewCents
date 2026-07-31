<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roadmap_step_id')->constrained()->cascadeOnDelete();
            $table->string('front');      // prompt / concept / question
            $table->text('back');         // explanation / answer
            $table->string('hint')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_cards');
    }
};
