<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgerdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgerdetails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ledger_id')->nullable();
            $table->string('lproduct', 400)->nullable();
            $table->integer('lqty')->nullable();
            $table->string('lprice', 400)->nullable();
            $table->integer('ltotal')->nullable();
            $table->tinyInteger('type')->comment('0 => ledger, 1 => Expense');
            $table->timestamps();
            $table->foreign('ledger_id')->references('id')->on('ledgers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledgerdetails');
    }
}
