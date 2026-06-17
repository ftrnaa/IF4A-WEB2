<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {

            if (!Schema::hasColumn('certificates', 'order_id')) {
                $table->foreignId('order_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('certificates', 'certificate_number')) {
                $table->string('certificate_number')
                    ->nullable()
                    ->after('user_id');
            }

            if (!Schema::hasColumn('certificates', 'qr_token')) {
                $table->uuid('qr_token')
                    ->nullable()
                    ->after('certificate_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {

            if (Schema::hasColumn('certificates', 'qr_token')) {
                $table->dropColumn('qr_token');
            }

            if (Schema::hasColumn('certificates', 'certificate_number')) {
                $table->dropColumn('certificate_number');
            }

            if (Schema::hasColumn('certificates', 'order_id')) {
                $table->dropConstrainedForeignId('order_id');
            }
        });
    }
};