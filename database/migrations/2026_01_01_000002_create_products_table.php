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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('link');
            $table->text('image')->nullable();
            $table->string('category')->nullable();
            $table->string('size')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('selling_price', 12, 2);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('admin_users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('admin_users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
