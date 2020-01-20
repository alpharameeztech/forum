<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumTheme extends Model
{
    public function default()
    {

        return $this->where('title', 'light')->first();
    }
}
