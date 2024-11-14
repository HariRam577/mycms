<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentTypesTable extends Migration
{
    public function up()
    {
        Schema::create('content_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the content type
            $table->timestamps();
        });

        // Create a default field settings for content types
        Schema::create('content_type_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_type_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type');
            $table->boolean('required')->default(false);
            $table->boolean('publish')->default(true); // Checkbox for publish
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('content_type_fields');
        Schema::dropIfExists('content_types');
    }
}

