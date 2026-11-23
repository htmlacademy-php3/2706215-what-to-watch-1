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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('role')->constrained();
        });

        $clientRoleId = DB::table('roles')
            ->where('name', '=', UserRole::CLIENT->value)
            ->value('id');
        DB::table('users')
            ->where('role', '=', UserRole::CLIENT->value)
            ->update(['role_id' => $clientRoleId]);

        $moderatorRoleId = DB::table('roles')
            ->where('name', '=', UserRole::MODERATOR->value)
            ->value('id');
        DB::table('users')
            ->where('role', '=', UserRole::MODERATOR->value)
            ->update(['role_id' => $moderatorRoleId]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', UserRole::cases())->default(UserRole::CLIENT->value)->after('avatar');
        });

        $clientRoleId = DB::table('roles')
            ->where('name', '=', UserRole::CLIENT->value)
            ->value('id');
        DB::table('users')
            ->where('role_id', '=', $clientRoleId)
            ->update(['role' => UserRole::CLIENT->value]);

        $moderatorRoleId = DB::table('roles')
            ->where('name', '=', UserRole::MODERATOR->value)
            ->value('id');
        DB::table('users')
            ->where('role_id', '=', $moderatorRoleId)
            ->update(['role' => UserRole::MODERATOR->value]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
    }
};
