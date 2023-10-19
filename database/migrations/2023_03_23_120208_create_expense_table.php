<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expense', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 50);
            $table->string('user_name', 50);
            $table->string('user_email', 50);
            $table->string('expense_category', 50);
            $table->string('amount', 50);
            $table->string('date', 50);
            $table->string('expense_name', 50);
            $table->string('is_income', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense');
    }
};
