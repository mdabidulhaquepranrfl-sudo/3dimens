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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->date('order_date');
            $table->date('exp_delv_date')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('thana_id')->nullable()->constrained('thana')->nullOnDelete();
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('orders');
    }
};
