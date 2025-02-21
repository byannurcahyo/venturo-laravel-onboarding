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
        Schema::create('m_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('m_user_roles_id')
                    ->comment('Fill with id from table m_user_roles');
            $table->string('name', 100)
                    ->comment('Fill with name of user');
            $table->string('email', 50)
                    ->comment('Fill with user email for login');
            $table->string('password', 255)
                    ->comment('Fill with user password');
            $table->string('phone_number', 25)
                ->default(null)
                ->comment('Fill with phone number of user ')
                ->nullable();
            $table->string('photo', 100)
                ->comment('Fill with user profile picture')
                ->nullable();
            $table->string('updated_security')
                ->comment('Fill with timestamp when user update password / email')
                ->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')
                ->nullable();
            $table->uuid('updated_by')
                ->nullable();
            $table->uuid('deleted_by')
                ->nullable();

            $table->index('m_user_roles_id');
            $table->index('email');
            $table->index('name');
            $table->index('updated_security');
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
