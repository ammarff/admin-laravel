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
        Schema::create('dokters', function (Blueprint $table) {
            $table->id();
            $table->string('NIK');
            $table->string('no_STR');
            $table->string('no_SIP');
            $table->string('nama_dokter');
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->date('tanggal_lahir');
            $table->string('rumah_sakit');
            $table->string('telp');
            $table->double('rate');
            $table->text('picturePath');


            $table->softDeletes();
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
        Schema::dropIfExists('dokters');
    }
};
