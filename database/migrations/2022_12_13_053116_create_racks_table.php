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
        Schema::create('racks', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100);
            $table->integer('customer_id');
            $table->integer('site_id');
            $table->integer('floor_id');
            $table->integer('room_id')->nullable();
            $table->integer('flagging')->default(1);
            $table->integer('status_id')->default(1);
            $table->string('rack_name',100);
            $table->string('rack_description',200)->nullable();
            $table->decimal('rack_default', 10, 2);
            $table->decimal('rack_va', 10, 2)->nullable();
            $table->integer('pic_id')->nullable();
            $table->timestamp('approve_date')->nullable();
            $table->decimal('rack_va_tmp', 10, 2)->nullable();
            $table->integer('pic_id_tmp')->nullable();
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
        Schema::dropIfExists('racks');
    }
};
