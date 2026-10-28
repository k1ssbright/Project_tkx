<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_views', function (Blueprint $table) {
            $table->boolean('is_sample')->default(false)->index();
        });
    }

    public function down(): void
    {
        Schema::table('product_views', function (Blueprint $table) {
            $table->dropIndex(['is_sample']);
            $table->dropColumn('is_sample');
        });
    }
};
