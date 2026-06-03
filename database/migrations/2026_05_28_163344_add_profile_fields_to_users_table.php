<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();

            $table->text('bio')->nullable();

            $table->string('avatar')->nullable();

            $table->boolean('notif_license')->default(true);
            $table->boolean('notif_cert')->default(true);
            $table->boolean('notif_promo')->default(true);
            $table->boolean('notif_news')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'first_name',
                'last_name',
                'phone',
                'city',
                'province',
                'bio',
                'avatar',
                'notif_license',
                'notif_cert',
                'notif_promo',
                'notif_news'
            ]);
        });
    }
};