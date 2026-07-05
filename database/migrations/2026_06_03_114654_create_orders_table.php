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
    Schema::create('orders', function (Blueprint $table) {

        $table->id();

        $table->foreignId('user_id')->constrained();
        $table->foreignId('batik_id')->constrained();

        $table->string('kode_order')->unique();

        // Identitas pembeli
        $table->string('nama');
        $table->string('email');
        $table->string('telepon');
        $table->string('nik')->nullable();

        // Perusahaan
        $table->string('perusahaan')->nullable();
        $table->string('npwp')->nullable();
        $table->string('bidang_usaha')->nullable();
        $table->text('alamat')->nullable();

        // Catatan
        $table->text('catatan')->nullable();

        // Transaksi
        $table->decimal('total',12,2);

        $table->enum('status',[
            'pending',
            'paid',
            'cancelled'
        ])->default('pending');

        $table->timestamps();
    });
}
};
