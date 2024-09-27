<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('book_name'); // Name of the book
            $table->string('author_name'); // Name of the author
            $table->date('date_published'); // Date published
            $table->text('comment')->nullable(); // User comment
            $table->unsignedTinyInteger('rating')->nullable(); // Rating (1 to 5 stars)
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
}
