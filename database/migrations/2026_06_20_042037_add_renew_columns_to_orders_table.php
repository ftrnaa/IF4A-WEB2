<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->boolean('is_renewal')
                  ->default(false)
                  ->after('status');

            $table->foreignId('renew_from_id')
                  ->nullable()
                  ->after('is_renewal')
                  ->constrained('orders')
                  ->nullOnDelete();

            $table->timestamp('renewed_at')
                  ->nullable()
                  ->after('renew_from_id');

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropForeign(['renew_from_id']);

            $table->dropColumn([
                'is_renewal',
                'renew_from_id',
                'renewed_at',
            ]);

        });
    }
};
