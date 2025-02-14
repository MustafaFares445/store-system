<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('summary')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->decimal('price');
            $table->unsignedInteger('view')->default(0);
            $table->integer('quantity');
            $table->foreignId('product_status_id')->constrained('product_statuses');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
