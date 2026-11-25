<?php

use App\Enum\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->enum('name', UserRole::cases())->default(UserRole::CLIENT->value)->unique();
            $table->timestamps(); // ?
        });

        DB::table('roles')->insert([
            ['name' => UserRole::CLIENT->value, 'created_at' => now()],
            ['name' => UserRole::MODERATOR->value, 'created_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
