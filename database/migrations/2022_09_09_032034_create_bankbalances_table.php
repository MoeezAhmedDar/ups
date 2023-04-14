<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankbalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankbalances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_id');
            $table->integer('amount')->default(0);
            $table->text('detail')->nullable();
            $table->timestamps();
            $table->foreign('bank_id')->references('id')->on('banks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bankbalances');
    }
}
