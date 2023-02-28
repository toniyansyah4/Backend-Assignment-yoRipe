<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table
                ->bigInteger('id_post')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('id_user')
                ->unsigned()
                ->index();
            $table->timestamps();

            $table
                ->foreign('id_post')
                ->references('id')
                ->on('blogs')
                ->onDelete('cascade')
                ->onUpdate('restrict');
            $table
                ->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
