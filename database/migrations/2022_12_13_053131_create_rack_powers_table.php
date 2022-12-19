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
        Schema::create('rack_powers', function (Blueprint $table) {
            $table->id();
            $table->integer('rack_id');
            $table->integer('created_id');
            $table->integer('approve_id')->nullable();
            $table->integer('flagging')->default(1);
            $table->integer('status_id');
            $table->decimal('rack_before', 10, 2)->nullable();
            $table->decimal('rack_va', 10, 2);
            $table->timestamp('process_date')->nullable();
            $table->timestamp('approve_date')->nullable();
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
        Schema::dropIfExists('rack_powers');
    }
};
