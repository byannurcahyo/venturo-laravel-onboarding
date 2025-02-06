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
        Schema::create('m_product', function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('m_product_category_id')
                ->comment('Fill with id from table m_product_category');
            $table->string('name', 150)
                ->comment('Fill with name of product');
            $table->double('price')
                ->comment('Fill price of product');
            $table->text('description')
                ->nullable()
                ->comment('Fill description of product');
            $table->text('photo')
                ->nullable()
                ->comment('Fill path of photo product');
            $table->tinyInteger('is_available')
                ->default(0)
                ->comment('Fill with "1" if product is available and "0" if product unavailable');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('deleted_by')->default(0);

            $table->index('m_product_category_id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_product');
    }
};
