<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete(); // admin
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();      // assignee
            $table->string('title');
            $table->text('description');
            $table->string('type')->default('task'); // task|mission
            $table->date('due_date')->nullable();
            $table->string('status')->default('assigned'); // assigned|submitted|reviewed
            $table->text('submission')->nullable();       // user's submission text/url
            $table->unsignedTinyInteger('rating')->nullable(); // 1..5 by admin
            $table->text('feedback')->nullable();              // admin feedback
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
