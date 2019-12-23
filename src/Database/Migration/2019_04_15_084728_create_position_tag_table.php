<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("position_tag", function (Blueprint $table) {
            $table->unsignedInteger('position_id')->nullable();
            $table->unsignedInteger('tag_id');
            $table->unsignedInteger('parent_position_id');
            $table->tinyInteger('type')->comment('0->position & parent position bilateral, 1->just position with tag');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("position_tag");
    }
}
