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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->string('oanda_trade_id')->unique()->nullable(); // OANDA trade ID
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('instrument'); // EUR_USD, XAU_USD, etc.
            $table->enum('type', ['BUY', 'SELL']);
            $table->enum('state', ['OPEN', 'CLOSED', 'PENDING'])->default('OPEN');
            $table->decimal('units', 15, 2); // Trade size
            $table->decimal('entry_price', 15, 5); // Entry price
            $table->decimal('current_price', 15, 5)->nullable(); // Current market price
            $table->decimal('exit_price', 15, 5)->nullable(); // Exit price (when closed)
            $table->decimal('stop_loss', 15, 5)->nullable();
            $table->decimal('take_profit', 15, 5)->nullable();
            $table->decimal('unrealized_pl', 15, 2)->default(0); // Unrealized P/L
            $table->decimal('realized_pl', 15, 2)->default(0); // Realized P/L
            $table->decimal('margin_used', 15, 2)->default(0);
            $table->string('strategy')->nullable(); // Strategy name (Manual, EMA, RSI, etc.)
            $table->json('signal_data')->nullable(); // Signal data if from strategy
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->text('close_reason')->nullable(); // Why trade was closed
            $table->json('oanda_data')->nullable(); // Full OANDA response data
            $table->timestamps();
            
            $table->index(['user_id', 'state']);
            $table->index(['instrument', 'state']);
            $table->index('opened_at');
            $table->index('closed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
