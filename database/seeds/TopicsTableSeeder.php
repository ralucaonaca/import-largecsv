<?php

use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            'title' => 'Topic One',
            'body' => 'Topic One test body'
        ]);

        DB::table('posts')->insert([
            'title' => 'Topic Two',
            'body' => 'Topic Two test body'
        ]);
    }
}
