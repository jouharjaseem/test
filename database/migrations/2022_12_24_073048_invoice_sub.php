<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_sub', function (Blueprint $table) {
            $table->id();
            $table->decimal("amount",11,2);
            $table->decimal("tax_amount",11,2);
            $table->integer('qty');
            $table->decimal("tot_amount",11,2);
            $table->decimal("net_amount",11,2);
            $table->unsignedBigInteger('master_id');
            $table->timestamps();
            $table->foreign('master_id')->references('id')->on('invoice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_sub');
    }
};
