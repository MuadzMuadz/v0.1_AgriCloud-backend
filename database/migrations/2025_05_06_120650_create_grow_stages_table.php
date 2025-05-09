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
        Schema::create('grow_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_template_id')->constrained('crop_templates')->onDelete('cascade');
            $table->string('stage_name', 255);
            $table->integer('day_offset')->unsigned();
            $table->string('expected_action', 255)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grow_stages');
    }
};
