<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('phone')->nullable()->after('last_name');

            $table->string('city')->nullable()->after('phone');

            $table->string('province')->nullable()->after('city');

            $table->text('bio')->nullable()->after('province');

            $table->string('avatar')->nullable()->after('bio');

            $table->boolean('notif_license')->default(true);

            $table->boolean('notif_cert')->default(true);

            $table->boolean('notif_promo')->default(false);

            $table->boolean('notif_news')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'phone',
                'city',
                'province',
                'bio',
                'avatar',
                'notif_license',
                'notif_cert',
                'notif_promo',
                'notif_news',
            ]);
        });
    }
};