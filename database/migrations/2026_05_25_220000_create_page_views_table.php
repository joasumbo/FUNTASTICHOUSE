<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('path', 500);
            $table->string('page_name', 150)->nullable();
            $table->string('session_id', 100)->index();
            $table->string('ip', 45)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('source', 100)->nullable();
            $table->string('device', 20)->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
