<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("ci")->unique();
            $table->string("email");
            $table->string("name");
            $table->string("last_name");
            $table->string("phone_number")->unique();
            $table->enum("type", ["daily", "monthly", "special"]);
            $table->date("birth_date");
            $table->enum("gender", ["man", "woman"]);
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
        Schema::dropIfExists('clients');
    }
}
