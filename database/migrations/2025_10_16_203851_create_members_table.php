<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('password');  
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('city')->nullable();
            $table->string('state')->nullable(); 
            $table->string('country')->nullable(); 
            $table->boolean('verified')->default(value: false);
            $table->enum('subscription', ['Free', 'Paid'])->default('Free');
            $table->string('profile_photo')->nullable();
            $table->json('uploaded_files')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
