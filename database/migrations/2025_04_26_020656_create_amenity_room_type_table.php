<?php

use App\Models\Amenity;
use App\Models\RoomType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amenity_room_type', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Amenity::class)->constrained()->onDeleteCascade();
            $table->foreignIdFor(RoomType::class)->constrained()->onDeleteCascade();
            $table->timestamps();

            // Ensure unique combinations
            $table->unique(['amenity_id', 'room_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amenity_room_type');
    }
};
