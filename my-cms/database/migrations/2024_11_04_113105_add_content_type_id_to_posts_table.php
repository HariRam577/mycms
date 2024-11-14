<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContentTypeIdToPostsTable extends Migration
{
    public function up()
    {
        // Check if the column already exists before adding it
        if (!Schema::hasColumn('posts', 'content_type_id')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->foreignId('content_type_id')->constrained()->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        // Check if the column exists before dropping it
        if (Schema::hasColumn('posts', 'content_type_id')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropForeign(['content_type_id']); // Drop the foreign key constraint
                $table->dropColumn('content_type_id'); // Drop the column
            });
        }
    }
}

