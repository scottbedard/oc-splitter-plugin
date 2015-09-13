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
            $table->string('name')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->text('version_a_content')->nullable();
            $table->integer('version_a_impressions')->unsigned()->default(0);
            $table->integer('version_a_conversions')->unsigned()->default(0);
            $table->text('version_b_content')->nullable();
            $table->integer('version_b_impressions')->unsigned()->default(0);
            $table->integer('version_b_conversions')->unsigned()->default(0);
            $table->boolean('is_running')->default(false);
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
