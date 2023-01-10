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
        Schema::create('moveins', function (Blueprint $table) {
            $table->id();
            $table->string('code_movein', 100)->unique();
            $table->string('ticket_number',100)->nullable();
            $table->integer('customer_id');
            $table->string('pic_name',100);
            $table->string('pic_phone',20);
            $table->date('installation_date');
            $table->integer('site_id')->nullable();
            $table->integer('floor_id')->nullable();
            $table->integer('flagging')->default(1);
            $table->integer('status_id')->default(1);
            $table->integer('created_by_id');
            $table->integer('updated_by_id')->nullable();
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
        Schema::dropIfExists('moveins');
    }
};
