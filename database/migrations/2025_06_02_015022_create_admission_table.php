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
        Schema::create('admission', function (Blueprint $table) {
            $table->id();
            $table->string('admission_no', 50);
            $table->string('admission_day', 50);
            $table->string('admission_time', 50);
            $table->string('customer_class', 45);
            $table->string('customer_id', 45);
            $table->string('box_name', 45);
            $table->string('qr_code', 2048)->nullable();// Max length for URLs in browsers
            $table->boolean('billing_flg')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission');
    }
};
