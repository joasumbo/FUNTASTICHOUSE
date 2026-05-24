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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name_pt');
            $table->string('name_en');
            $table->text('description_pt');
            $table->text('description_en');
            $table->text('short_description_pt')->nullable();
            $table->text('short_description_en')->nullable();
            $table->decimal('base_price', 8, 2);
            $table->decimal('weekend_price', 8, 2);
            $table->unsignedTinyInteger('max_guests')->default(4);
            $table->unsignedTinyInteger('bedrooms')->default(2);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
