<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('can_read_members')->default(false);
            $table->boolean('can_create_members')->default(false);
            $table->boolean('can_update_members')->default(false);
            $table->boolean('can_delete_members')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'can_read_members',
                'can_create_members',
                'can_update_members',
                'can_delete_members',
            ]);
        });
    }
};
