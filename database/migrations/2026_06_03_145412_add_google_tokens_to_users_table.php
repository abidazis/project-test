<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk menambah kolom token.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom text untuk menyimpan token JSON dari Google
            $table->text('google_token')->nullable()->after('password');
        });
    }

    /**
     * Membatalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_token');
        });
    }
};