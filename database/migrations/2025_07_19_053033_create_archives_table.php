<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->string('cif');
            $table->string('rekening_pinjaman');
            $table->string('nama');
            $table->string('wilayah');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->decimal('plafond', 15, 2);
            $table->enum('kategori', ['berkas', 'spk', 'proposal', 'jaminan']);
            $table->string('file');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archives');
    }
};
