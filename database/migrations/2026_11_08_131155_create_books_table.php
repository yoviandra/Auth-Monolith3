
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('isbn')->unique();

            $table->foreignId('author_id')
                ->constrained('authors')
                ->cascadeOnDelete();

            $table->foreignId('publisher_id')
                ->constrained('publishers')
                ->cascadeOnDelete();

            $table->year('publication_year')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('price', 12, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

