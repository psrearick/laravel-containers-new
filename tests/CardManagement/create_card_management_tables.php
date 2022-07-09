<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up() : void
    {
        Schema::create('cards', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price')->nullable();
            $table->timestamps();
        });

        Schema::create('collections', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('total_value')->nullable();
            $table->timestamps();
        });
    }
};
