<?php

use App\Enums\AttributesFilterTypes;
use App\Enums\AttributesTypes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type' , array_map(fn($case) => $case->value, AttributesTypes::cases()));
            $table->enum('filter_type' , array_map(fn($case) => $case->value, AttributesFilterTypes::cases()));
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
