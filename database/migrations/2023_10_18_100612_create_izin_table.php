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
        Schema::create('izin', function (Blueprint $table) {
            $table->id();
	        $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users'); // Menambahkan foreign key ke tabel users
            $table->date('tgl_izin');
            $table->enum('status', ['I', 'S']); // Kolom "status" dengan pilihan i (izin) atau s (sakit)
            $table->text('keterangan');
            $table->tinyInteger('status_approved')->default(0); // Kolom "status_approved" dengan pilihan 0, 1, 2
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin');
    }
};
