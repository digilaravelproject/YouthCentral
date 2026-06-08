<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($days as $day) {
                $table->string($day . '_open')->nullable()->after('pinterest_link');
                $table->string($day . '_close')->nullable()->after($day . '_open');
                $table->boolean($day . '_closed')->default(false)->after($day . '_close');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $columns = [];
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($days as $day) {
                $columns[] = $day . '_open';
                $columns[] = $day . '_close';
                $columns[] = $day . '_closed';
            }
            $table->dropColumn($columns);
        });
    }
};


