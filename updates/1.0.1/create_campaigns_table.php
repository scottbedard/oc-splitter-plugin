<?php namespace Bedard\Splitter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateCampaignsTable extends Migration
{

    public function up()
    {
        Schema::create('bedard_splitter_campaigns', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('file_name');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_splitter_campaigns');
    }

}
