<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_fields_table.php
public function up()
{
    Schema::create('fields', function (Blueprint $table) {
        $table->id();
        $table->foreignId('content_type_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->string('type'); // e.g., text, textarea, date, etc.
        $table->boolean('required')->default(false);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
