<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('establishments', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->string('name');
            $table->string('email');
            $table->string('passwordHash');
            $table->string('photoUrl')->nullable();
            $table->unsignedBigInteger('streetId');
            $table->string('addressNumber');
            $table->boolean('deleted')->default(false);
            $table->timestamps();

            $table->primary('uuid');
            $table
                ->foreign('streetId')
                ->references('id')
                ->on('streets')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('establishments');
    }
}
