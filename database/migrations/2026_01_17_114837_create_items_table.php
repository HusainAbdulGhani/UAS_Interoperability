<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('item_code')->unique();
            $table->string('name');
            $table->integer('stock')->default(0);
            $table->string('location');
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('items'); }
};