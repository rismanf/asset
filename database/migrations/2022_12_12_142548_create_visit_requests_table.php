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
        Schema::create('visit_requests', function (Blueprint $table) {
            $table->id();
            $table->string('kode_request_visit', 100);
            $table->integer('customer_id');
            $table->integer('site_id');
            $table->integer('floor_id');
            $table->integer('visit_activity_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('remark', 200);
            $table->integer('status');
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
        Schema::dropIfExists('visit_requests');
    }
};
