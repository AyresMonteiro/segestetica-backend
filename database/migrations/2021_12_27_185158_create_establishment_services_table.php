<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablishmentServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('establishment_services', function (Blueprint $table) {
            $table->unsignedBigInteger('serviceId');
            $table->foreignUuid('establishmentUuid');
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->primary(['serviceId', 'establishmentUuid']);
            $table->foreign('serviceId')
                ->references('id')
                ->on('services')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('establishmentUuid')
                ->references('uuid')
                ->on('establishments')
                ->onDelete('cascade')
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
        Schema::dropIfExists('establishment_services');
    }
}
