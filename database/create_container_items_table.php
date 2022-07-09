<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends  Migration
{
    public function up() : void
    {
        Schema::create('container_items', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id');
            $table->string('container_type');
            $table->foreignId('item_id');
            $table->string('item_type');
            $table->integer('quantity');
            $table->json('parameters');
            $table->timestamps();
        });
    }
};
