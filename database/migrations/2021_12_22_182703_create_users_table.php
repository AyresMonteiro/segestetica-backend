<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->string('name');
            $table->string('lastName');
            $table->string('email');
            $table->string('passwordHash');
            $table->string('phoneNumber');
            $table->unsignedBigInteger('neighborhoodId');
            $table->boolean('deleted')->default(false);
            $table->timestamp('emailConfirmation')->nullable()->default(null);
            $table->timestamps();

            $table->primary('uuid');
            $table
                ->foreign('neighborhoodId')
                ->references('id')
                ->on('neighborhoods')
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
        Schema::dropIfExists('users');
    }
}
