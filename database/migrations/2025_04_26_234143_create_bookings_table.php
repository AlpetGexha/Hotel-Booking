<?php

use App\Models\Customer;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RoomType::class)->constrained()->onDeleteCascade();
            $table->foreignIdFor(Room::class)->nullable()->constrained()->onDeleteCascade();
            $table->foreignIdFor(Customer::class)->constrained()->onDeleteCascade();
            $table->unsignedTinyInteger('guests');
            $table->date('check_in');
            $table->date('check_out');
            $table->decimal('total_price', 10, 2);
            $table->text('special_requests')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
