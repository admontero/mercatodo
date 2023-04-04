<?php

use App\Models\UserStatuses\ActiveStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 60);
            $table->string('last_name', 80);
            $table->string('email', 100)->unique();
            $table->enum('document_type', ['CC', 'CE', 'P'])->nullable();
            $table->string('document', 30)->nullable();
            $table->string('address', 120)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('cell_phone', 25)->nullable();
            $table->string('status')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
