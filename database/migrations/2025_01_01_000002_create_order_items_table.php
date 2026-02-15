<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->text('source_url');
            $table->string('source_site')->nullable();
            $table->string('scraped_title')->nullable();
            $table->decimal('scraped_price', 12, 2)->nullable();
            $table->string('scraped_currency', 3)->nullable();
            $table->string('scraped_image')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->decimal('converted_price', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
