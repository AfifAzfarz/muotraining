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
        Schema::create('negeris', function (Blueprint $table) {
            $table->id();
            $table->string('attribute_id')->nullable();
            $table->text('nama');
            $table->text('kod_negeri')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negeris');
    }
};
