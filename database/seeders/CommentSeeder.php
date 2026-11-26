<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Film;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $films = Film::all();
        $users = User::all();

        Comment::factory()
            ->count(100)
            ->recycle($films)
            ->recycle($users)
            ->create();
    }
}
