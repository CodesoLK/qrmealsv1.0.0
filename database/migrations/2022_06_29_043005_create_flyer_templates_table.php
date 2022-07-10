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
        Schema::create('flyer_templates', function (Blueprint $table) {
            $table->id();
            $table->integer('flyer_id');
            $table->string('type');
            $table->longText('value');
            $table->integer('width');
            $table->string('position');
            $table->longText('style');
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
        Schema::dropIfExists('flyer_templates');
    }
};
