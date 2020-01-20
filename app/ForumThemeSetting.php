<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumThemeSetting extends Model
{
    public function theme()
    {
        return $this->belongsTo(ForumTheme::class, 'forum_theme_id');
    }
}
