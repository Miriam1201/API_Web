<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;


class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'username' => 'Usuario1',
            'email' => 'usuario1@example.com',
            'profile_image' => '/images/avatar/avatar1.png',
            'date' => now(),
            'game' => 'Genshin Impact',
            'content' => 'Esta es una publicación de prueba.',
            'images' => ['/images/community/post1.png'],
            'tags' => ['prueba', 'genshin'],
            'likes' => 10,
            'comments' => 5,
            'shares' => 3,
        ]);

        Post::create([
            'username' => 'Usuario2',
            'email' => 'usuario2@example.com',
            'profile_image' => '/images/avatar/avatar2.png',
            'date' => now(),
            'game' => 'Genshin Impact',
            'content' => 'Esta es otra publicación de prueba.',
            'images' => ['/images/community/post2.png'],
            'tags' => ['honkai', 'impact'],
            'likes' => 15,
            'comments' => 8,
            'shares' => 5,
        ]);

        Post::create([
            'username' => 'Usuario3',
            'email' => 'usuario3@example.com',
            'profile_image' => '/images/avatar/avatar2.png',
            'date' => now(),
            'game' => 'Genshin Impact',
            'content' => 'Esta es una tercera publicación de prueba.',
            'images' => ['/images/community/post1.png'],
            'tags' => ['tears', 'themis'],
            'likes' => 20,
            'comments' => 10,
            'shares' => 7,
        ]);
    }
}
