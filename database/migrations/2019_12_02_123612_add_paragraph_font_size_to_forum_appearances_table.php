<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParagraphFontSizeToForumAppearancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forum_appearances', function (Blueprint $table) {
            $table->unsignedSmallInteger('paragraph_font_size')->after('paragraph_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forum_appearances', function (Blueprint $table) {
            //
        });
    }
}
