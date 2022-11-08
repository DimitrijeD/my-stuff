<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\UserSettings;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('open_all_chats_on_new_message')->default(UserSettings::OPEN_ALL_CHATS_ON_NEW_MESSAGE);
            $table->boolean('show_only_important_notifications')->default(UserSettings::SHOW_ONLY_IMPORTANT_NOTIFICATIONS);
            $table->enum('theme', UserSettings::THEMES)->default(UserSettings::DEFAULT_THEME);
            $table->json('colorz')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_settings');
    }
};
