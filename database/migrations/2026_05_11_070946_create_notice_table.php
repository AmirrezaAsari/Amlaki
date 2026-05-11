<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\NoticeTypeEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notice', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('type')->enum('type', NoticeTypeEnum::class);
            $table->integer('price_per_meter')->nullable();
            $table->integer('total_price')->nullable();
            $table->integer('pre_pay')->nullable();
            $table->integer('monthly_pay')->nullable();
            $table->integer('meterage');
            $table->string('phone_number');
            $table->string('city');
            $table->string('district');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notice');
    }
};
