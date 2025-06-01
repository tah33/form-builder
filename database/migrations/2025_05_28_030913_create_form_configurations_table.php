<?php

use App\Models\Form;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId(Form::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('type')->default('text');
            $table->string('name');
            $table->string('label')->nullable();
            $table->string('placeholder')->nullable();
            $table->boolean('required')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_configurations');
    }
};
