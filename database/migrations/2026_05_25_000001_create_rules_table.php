<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['availability', 'pricing']);
            $table->enum('trigger_metric', [
                'confirmed_reservations',
                'pending_reservations',
                'total_reservations',
                'occupancy_pct',
            ]);
            $table->enum('trigger_operator', ['gte', 'lte', 'gt', 'lt', 'eq']);
            $table->decimal('trigger_value', 8, 2);
            $table->enum('action_type', [
                'block_date',
                'unblock_date',
                'price_increase',
                'price_decrease',
            ]);
            $table->decimal('action_value', 8, 2)->nullable();
            $table->enum('action_unit', ['fixed', 'percent'])->default('fixed');
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('priority')->default(0);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};
