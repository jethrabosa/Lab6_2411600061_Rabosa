<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasProductForeignKey = collect(Schema::getForeignKeys('inventory_transactions'))
            ->contains(fn (array $foreignKey) => in_array('product_id', $foreignKey['columns'], true));

        if (! $hasProductForeignKey) {
            Schema::table('inventory_transactions', function (Blueprint $table) {
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->restrictOnDelete();
            });
        }
    }

    public function down(): void
    {
        $hasProductForeignKey = collect(Schema::getForeignKeys('inventory_transactions'))
            ->contains(fn (array $foreignKey) => in_array('product_id', $foreignKey['columns'], true));

        if ($hasProductForeignKey) {
            Schema::table('inventory_transactions', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
            });
        }
    }
};
