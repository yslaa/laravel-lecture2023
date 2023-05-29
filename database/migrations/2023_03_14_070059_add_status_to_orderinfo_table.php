<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToOrderinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orderinfo', function (Blueprint $table) {
            $table->enum('status', ['Processing', 'Delivered', 'Canceled']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orderinfo', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
