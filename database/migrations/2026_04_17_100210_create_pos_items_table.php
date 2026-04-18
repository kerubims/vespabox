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
        Schema::create('pos_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_transaction_id')->constrained()->onDelete('cascade');
            $table->enum('item_type', ['jasa', 'sparepart']);
            $table->foreignId('item_id')->nullable()->constrained('spareparts')->onDelete('set null');
            $table->string('item_name');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_items');
    }
};
