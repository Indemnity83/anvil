<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('port')->default(8080);
            $table->string('directory')->default('/public');
            $table->string('status')->default('installing');
            $table->string('repository')->nullable();
            $table->string('repository_provider')->nullable();
            $table->string('repository_branch')->nullable();
            $table->string('repository_status')->nullable();
            $table->longText('deploy_script')->nullable();
            $table->longText('deploy_status')->nullable();
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
        Schema::dropIfExists('sites');
    }
}
