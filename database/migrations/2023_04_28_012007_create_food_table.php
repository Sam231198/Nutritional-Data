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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->text("code");
            $table->text("status")->nullable();
            $table->text("imported_t")->nullable();
            $table->text("url")->nullable();
            $table->text("creator")->nullable();
            $table->text("created_t")->nullable();
            $table->text("last_modified_t")->nullable();
            $table->text("product_name")->nullable();
            $table->text("quantity")->nullable();
            $table->text("brands")->nullable();
            $table->text("categories")->nullable();
            $table->text("labels")->nullable();
            $table->text("cities")->nullable();
            $table->text("purchase_places")->nullable();
            $table->text("stores")->nullable();
            $table->text("ingredients_text")->nullable();
            $table->text("traces")->nullable();
            $table->text("serving_size")->nullable();
            $table->text("serving_quantity")->nullable();
            $table->text("nutriscore_score")->nullable();
            $table->text("nutriscore_grade")->nullable();
            $table->text("main_category")->nullable();
            $table->text("image_url")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
