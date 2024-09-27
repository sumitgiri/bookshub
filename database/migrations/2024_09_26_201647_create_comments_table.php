<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->foreignId('book_id')->constrained()->onDelete('cascade'); // Foreign key referencing books
            $table->text('comment'); // User's comment
            $table->unsignedTinyInteger('rating'); // Rating from 1 to 5 stars
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
