<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->string('image_url')->nullable();
            $table->uuid('user_id');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
