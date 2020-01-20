<?php

use Illuminate\Database\Seeder;

class ForumThemesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $countThemes = App\ForumTheme::count();

            if($countThemes == 0){
                $theme = new \App\ForumTheme;

                $theme->title = "light";

                $theme->css_class = "light";

                $theme->preview_img = "/img/themes/light.png";

                $theme->save();

                $theme = new \App\ForumTheme;

                $theme->title = "dark";

                $theme->css_class = "dark";

                $theme->preview_img = "/img/themes/dark.png";

                $theme->save();
            }else{

                $lightTheme = App\ForumTheme::where('title', 'light')->first();
                $lightTheme->preview_img = "/img/themes/light.png";
                $lightTheme->save();

                $darkTheme = App\ForumTheme::where('title', 'dark')->first();
                $darkTheme->preview_img = "/img/themes/dark.png";
                $darkTheme->save();
            }

    }
}
