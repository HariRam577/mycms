<?php

// database/seeders/ContentTypeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ContentTypeSeeder extends Seeder
{
    public function run()
    {
        $contentType = DB::table('content_types')->insertGetId([
            'name' => 'News',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('content_type_fields')->insert([
            [
                'content_type_id' => $contentType,
                'name' => 'title',
                'type' => 'text',
                'required' => true,
                'publish' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'content_type_id' => $contentType,
                'name' => 'body',
                'type' => 'ckeditor',
                'required' => true,
                'publish' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

