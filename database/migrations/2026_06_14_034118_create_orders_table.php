<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
          if (Schema::hasTable('orders')) {
        return;
        }
        
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('batik_id')
                ->constrained('batiks')
                ->cascadeOnDelete();

            $table->string('kode_order')->unique();

            $table->string('nama');
            $table->string('email');
            $table->string('telepon');

            $table->string('nik')->nullable();

            $table->string('perusahaan')->nullable();
            $table->string('npwp')->nullable();
            $table->string('bidang_usaha')->nullable();

            $table->text('alamat')->nullable();
            $table->text('catatan')->nullable();

            $table->decimal('total', 12, 2);

            $table->enum('status', [
                'pending',
                'paid',
                'cancelled'
            ])->default('pending');

            $table->string('payment_type')->nullable();
            $table->string('payment_channel')->nullable();

            $table->timestamp('license_expired_at')->nullable();

            $table->boolean('is_renewal')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};