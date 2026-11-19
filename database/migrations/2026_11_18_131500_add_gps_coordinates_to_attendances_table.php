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
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (!Schema::hasColumn('attendances', 'checkin_latitude')) {
                    $table->decimal('checkin_latitude', 10, 7)->nullable()->after('login_time');
                    $table->decimal('checkin_longitude', 10, 7)->nullable()->after('checkin_latitude');
                    $table->text('checkin_address')->nullable()->after('checkin_longitude');
                }
                if (!Schema::hasColumn('attendances', 'checkout_latitude')) {
                    $table->decimal('checkout_latitude', 10, 7)->nullable()->after('logout_time');
                    $table->decimal('checkout_longitude', 10, 7)->nullable()->after('checkout_latitude');
                    $table->text('checkout_address')->nullable()->after('checkout_longitude');
                }
                if (!Schema::hasColumn('attendances', 'device_info')) {
                    $table->string('device_info')->nullable()->after('user_agent');
                }
                if (!Schema::hasColumn('attendances', 'source')) {
                    $table->string('source', 30)->default('web')->after('device_info');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                $columns = [
                    'checkin_latitude',
                    'checkin_longitude',
                    'checkin_address',
                    'checkout_latitude',
                    'checkout_longitude',
                    'checkout_address',
                    'device_info',
                    'source',
                ];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('attendances', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
