
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();

            $table->string('borrowing_number')->unique();

            $table->foreignId('member_id')
                ->constrained('members')
                ->cascadeOnDelete();

            $table->date('borrowed_at');
            $table->date('due_date');
            $table->string('status')->default('borrowed');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};

