<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('domain')->unique()->nullable();
            $table->boolean('emails')->default(1);
            $table->enum('http', ['http://', 'https://']);
            $table->string('ftp')->nullable();
            $table->string('ftpUser')->nullable();
            $table->string('ftpPass')->nullable();
            $table->string('ftpDir')->nullable();
            $table->string('preview')->nullable();
            $table->string('config_logo')->nullable();
            $table->string('config_name')->nullable();
            $table->string('config_email')->nullable();
            $table->text('config_description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
}
