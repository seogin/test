<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('can_create')->nullable()->after('locked_until');
            $table->boolean('can_read')->nullable()->after('can_create');
            $table->boolean('can_update')->nullable()->after('can_read');
            $table->boolean('can_delete')->nullable()->after('can_update');
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['can_create', 'can_read', 'can_update', 'can_delete']);
        });
    }
};
