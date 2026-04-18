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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('plat_nomor');
            $table->string('kendaraan');
            $table->text('keluhan');
            $table->date('tanggal');
            $table->time('jam');
            $table->enum('status', ['Menunggu', 'Dikonfirmasi', 'Sedang Dikerjakan', 'Selesai', 'Dibatalkan'])->default('Menunggu');
            $table->boolean('is_reviewed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
