<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {

        if (!Schema::hasColumn('orders', 'renew_from_id')) {
            $table->foreignId('renew_from_id')
                ->nullable()
                ->constrained('orders')
                ->nullOnDelete();
        }

        if (!Schema::hasColumn('orders', 'renewed_at')) {
            $table->timestamp('renewed_at')->nullable();
        }

    });
}
};