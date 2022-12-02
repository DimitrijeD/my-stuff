<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Chat\ChatRole;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_participants', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('invited_by_user_id');
            $table->integer('group_id');
            $table->boolean('accepted')->default(0);
            $table->integer('last_message_seen_id')->nullable();
            $table->string('participant_role')->default(ChatRole::PARTICIPANT);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_participants');
    }
};
