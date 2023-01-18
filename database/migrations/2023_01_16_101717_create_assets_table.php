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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_name', 150);
            $table->string('asset_code', 30)->nullable();
            $table->string('asset_class', 30)->nullable();
            $table->string('asset_facility', 10)->nullable();
            $table->string('asset_type', 50)->nullable();
            $table->string('serial_number', 50)->nullable();
            $table->bigInteger('price')->nullable();
            $table->string('old_tag', 30)->nullable();
            $table->string('description', 250)->nullable();
            $table->string('sap_number', 100)->nullable();
            $table->string('do_number', 100)->nullable();
            $table->date('do_date')->nullable();
            $table->string('po_number', 100)->nullable();
            $table->date('po_date')->nullable();
            $table->string('po_number_maintenance', 100)->nullable();
            $table->date('po_date_maintenance')->nullable();
            $table->date('dep_start_date')->nullable();
            $table->date('dep_end_date')->nullable();
            $table->date('buy_date')->nullable();
            $table->string('polis', 100)->nullable();
            $table->string('condition', 100)->nullable();
            $table->string('capacity', 100)->nullable();
            $table->string('u_space', 100)->nullable();
            $table->date('tahun_pembuatan', 100)->nullable();
            $table->date('tahun_instalasi', 100)->nullable();
            $table->date('tahun_operasi', 100)->nullable();
            $table->string('remarks', 250)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')
                ->references('id')->on('asset_categories')->onDelete('set null');
            $table->unsignedBigInteger('brand_id')->nullable(); //merek
            $table->foreign('brand_id')
                ->references('id')->on('brands')->onDelete('set null');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->foreign('vendor_id')
                ->references('id')->on('vendors')->onDelete('set null');
            $table->unsignedBigInteger('bisnis_unit_id')->nullable();
            $table->foreign('bisnis_unit_id')
                ->references('id')->on('bisnis_units')->onDelete('set null');
            $table->unsignedBigInteger('site_id');
            $table->foreign('site_id')
                ->references('id')->on('sites');
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->foreign('floor_id')
                ->references('id')->on('floors')->onDelete('set null');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id')
                ->references('id')->on('rooms')->onDelete('set null');
            $table->integer('asset_file_id')->nullable();
            $table->integer('created_by_id')->default(1);
            $table->integer('updated_by_id')->nullable();
            $table->integer('deleted_by_id')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
};
