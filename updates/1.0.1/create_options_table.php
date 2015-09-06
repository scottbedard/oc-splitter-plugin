<?php namespace Bedard\Splitter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateOptionsTable extends Migration
{

    public function up()
    {
        Schema::create('bedard_splitter_options', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('campaign_id')->unsigned()->nullable();
            $table->string('name');
            $table->text('source');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_splitter_options');
    }

}
