<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('roleid');
            $table->string('role',30)->unique();
        });
        DB::table('roles')->insert(array('roleid' => '1', 'role' => 'Administrador'));
        DB::table('roles')->insert(array('roleid' => '2', 'role' => 'Usuario'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
