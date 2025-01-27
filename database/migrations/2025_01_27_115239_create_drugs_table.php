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
        Schema::create('drugs', function (Blueprint $table) {
            $table->id();
			$table->string('url');
			$table->string('title_ka');
			$table->string('title_en');
			$table->longText('all');
			$table->string('img')->nullable();
			$table->string('company')->nullable();
			$table->string('country')->nullable();
			$table->string('release_form_initial')->nullable();
			$table->string('issue_mode')->nullable();
			$table->string('substance')->nullable();
			$table->string('properties')->nullable();
			$table->string('dosage')->nullable();
			$table->string('notes')->nullable();
			$table->string('lactation')->nullable();
			$table->string('storage')->nullable();
			$table->string('release_form')->nullable();
			$table->string('made_by')->nullable();
	        $table->foreignId('substance_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vidals');
    }
};
