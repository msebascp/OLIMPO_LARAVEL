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
        Schema::create('imc_records', function (Blueprint $table) {
            $table->id();
            $table->integer('weight')->nullable();
            $table->integer('height')->nullable();
            $table->date('weighing_date');
            $table->integer('imc')->nullable();
            $table->foreignId('customer_id')->constrained('customers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imc_records');
    }
};
