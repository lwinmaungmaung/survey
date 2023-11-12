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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type');
            $table->boolean('required')->default(false);
            $table->integer('order')->default(0);
            $table->json('options')->nullable();
            $table->string('default')->nullable();
            $table->string('placeholder')->nullable();
            $table->string('help_text')->nullable();
            $table->string('validation')->nullable();
            $table->string('validation_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
