<?php

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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->dateTime('startdate');
            $table->dateTime('enddate');
            $table->integer('startticketsqty');
            $table->integer('currentticketsqty');
            $table->decimal('currentprice', 8, 2);
            $table->string('address');
            //$table->foreignId('category_id')->constrained('category');
           // $table->foreignId('city_id')->constrained('city');
           // $table->foreignId('owner_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
