<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEmailConfirmationTypeInEstablishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('establishments', function (Blueprint $table) {
            $table->dropColumn("emailConfirmation");
        });

        Schema::table('establishments', function (Blueprint $table) {
            $table->timestamp("emailConfirmation")->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('establishments', function (Blueprint $table) {
            $table->dropColumn("emailConfirmation");
        });

        Schema::table('establishments', function (Blueprint $table) {
            $table->boolean('emailConfirmation')->default(false);
        });
    }
}
