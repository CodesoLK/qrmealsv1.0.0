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
        Schema::create('flyers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('template_name');
            $table->integer('layout_width');
            $table->integer('layout_height');
            $table->string('theme_type');
            $table->string('theme_value');
            $table->longText('template_cover');
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
        Schema::dropIfExists('flyers');
    }
};
