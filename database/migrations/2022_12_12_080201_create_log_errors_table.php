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
        Schema::create('log_errors', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 20)->nullable();
            $table->string('ip', 16)->nullable();
            $table->string('menu', 100);
            $table->string('event', 100)->nullable();
            $table->text('extra')->nullable();
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
        Schema::dropIfExists('log_errors');
    }
};
