<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('is_active')->default(true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
