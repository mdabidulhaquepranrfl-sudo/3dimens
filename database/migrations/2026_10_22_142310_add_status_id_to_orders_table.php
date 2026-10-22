<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'status_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('status_id')->nullable()->after('thana_id')->constrained('status')->nullOnDelete();
            });
        }

        // Map existing status string to status_id
        $statuses = DB::table('status')->get();
        foreach ($statuses as $st) {
            DB::table('orders')->where('status', $st->tag)->update(['status_id' => $st->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
    }
};
