<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('admins', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('first_name', 25);
			$table->string('last_name', 25);
			$table->string('username', 25)->unique();
			$table->string('email')->unique();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->unsignedBigInteger('status')->default(1);
			$table->integer('avatar_id')->default(2)->nullable();
			$table->rememberToken();
			$table->softDeletes();
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
        Schema::dropIfExists('admins');
    }
}
