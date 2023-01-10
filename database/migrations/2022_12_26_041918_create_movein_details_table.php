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
        Schema::create('movein_details', function (Blueprint $table) {
            $table->id();
            $table->integer('movein_id');
            $table->string('item_name',100);
            $table->string('item_description',300)->nullable();
            $table->decimal('item_va', 10, 2);
            $table->integer('rack_id');
            $table->decimal('rack_va_before', 10, 2)->nullable();
            $table->decimal('rack_va_affter', 10, 2)->nullable();
            $table->integer('flagging')->default(1);
            $table->integer('status_id')->default(1);
            $table->integer('created_by_id');
            $table->integer('updated_by_id')->nullable();
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
        Schema::dropIfExists('movein_details');
    }
};
