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
        Schema::create('visit_request_details', function (Blueprint $table) {
            $table->id();
            $table->integer('visit_request_id');
            $table->string('qr', 100);
            $table->string('visitor_name', 100);
            $table->string('company', 100);
            $table->integer('visit_role_id');
            $table->integer('cat_identy_id');
            $table->string('identy', 200);
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('visit_request_details');
    }
};
