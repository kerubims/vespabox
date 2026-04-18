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
        Schema::create('restocks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_restock')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // admin who did the restock
            $table->text('catatan')->nullable();
            $table->integer('total_item')->default(0);
            $table->integer('total_qty')->default(0);
            $table->timestamps();
        });

        Schema::create('restock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restock_id')->constrained()->onDelete('cascade');
            $table->foreignId('sparepart_id')->constrained('spareparts')->onDelete('cascade');
            $table->integer('qty');
            $table->integer('harga_beli')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restock_items');
        Schema::dropIfExists('restocks');
    }
};
