<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumAppearance extends Model
{
    protected $fillable = [

        'primary_button_color',
        'link_color',
        'heading_color',
        'paragraph_color'
    ];
}
