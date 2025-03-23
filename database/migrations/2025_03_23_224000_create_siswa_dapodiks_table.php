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
        Schema::create('siswa_dapodiks', function (Blueprint $table) {
            $table->id();
            $table->string('sekolah_asal');
            $table->string('nama');
            $table->string('jenjang');
            $table->string('status');
            $table->string('nisn')->nullable();
            $table->string('npsn_sekolah_sekarang')->nullable();
            $table->string('tingkat');
            $table->string('sekolah_smp')->nullable();
            $table->string('peserta_didik_id');
            $table->string('rombel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_dapodiks');
    }
};
