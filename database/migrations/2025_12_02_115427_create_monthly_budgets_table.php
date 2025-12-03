<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('monthly_budgets', function (Blueprint $table) {
            $table->id();

            // Relasi ke users
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Relasi ke categories
            $table->foreignId('category_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('month'); // e.g. "January"
            $table->year('year');
            $table->decimal('planned_amount', 15, 2);
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('monthly_budgets');
    }
};
