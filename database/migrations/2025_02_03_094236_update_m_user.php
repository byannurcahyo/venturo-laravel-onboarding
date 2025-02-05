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
        Schema::table('m_users', function (Blueprint $table) {
            $table->string('address', 255)
                ->after('phone_number')
                ->nullable()
                ->comment('Fill with user address');
            $table->string('city', 100)
                ->after('address')
                ->nullable()
                ->comment('Fill with user city');
            $table->string('country', 100)
                ->after('city')
                ->nullable()
                ->comment('Fill with user country');
            $table->string('phone_number', 25)
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_users');
    }
};
