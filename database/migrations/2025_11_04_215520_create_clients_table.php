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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('client_number')->unique()->nullable(); // generated after insert
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('company')->nullable();
            $table->string('role')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('street')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable(); // user id
            $table->json('custom_fields')->nullable();
            $table->timestamps();
        });

        // create fulltext index for MySQL
        // Note: MySQL supports FULLTEXT on MyISAM/InnoDB (5.6+). Adjust if your engine differs.
        //DB::statement('ALTER TABLE clients ADD FULLTEXT fulltext_index (name, email, company)');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
