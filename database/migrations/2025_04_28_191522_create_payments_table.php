<?php

declare(strict_types=1);

use App\Enum\PaymentMethod;
use App\Enum\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 8, 2);
            $table->morphs('paymentable');
            $table->string('reference', 255)->nullable();
            $table->string('provider', 255)->nullable();
            $table->string('method')->default(PaymentMethod::CASH->value);
            $table->string('status')->default(PaymentStatus::PENDING->value);
            $table->string('currency', 255)->default('EUR');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
