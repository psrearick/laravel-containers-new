<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up() : void
    {
        Schema::create('items', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }
};
