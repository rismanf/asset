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
            $table->integer('flagging')->default(1);
            $table->integer('status_id')->default(1);
            $table->decimal('rack_before', 10, 2)->default(0);
            $table->decimal('rack_va', 10, 2)->default(0);
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('rack_powers');
    }
};
