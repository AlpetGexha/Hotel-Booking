<?php

declare(strict_types=1);

use App\Enum\BookingStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('status')->default(BookingStatus::CONFIRMED->value)->after('special_requests');
            $table->decimal('total_payed', 8, 2)->nullable()->after('total_price');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('total_payed');
        });
    }
};
