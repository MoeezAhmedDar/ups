<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotationdetails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_id');
            $table->string('item')->nullable();
            $table->integer('quantity')->nullable();
            $table->double('price', 15, 8)->nullable();
            $table->double('discount', 15, 8)->nullable();
            $table->double('gst', 15, 8)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('quotation_id')->references('id')->on('quotations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotationdetails');
    }
}
