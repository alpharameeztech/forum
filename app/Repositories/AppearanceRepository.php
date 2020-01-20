<?php

namespace App\Repositories;

use App\ForumAppearance;
use App\Interfaces\AppearanceRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class AppearanceRepository implements AppearanceRepositoryInterface
{
    protected $shop_id;

    protected $default_primary_button_color = '#3490dc';

    protected $default_link_color = '#3490dc';

    protected $default_heading_color = '#3490dc';

    protected $default_paragraph_color = '#000000';

    public function __construct()
    {
        $this->shop_id = Cache::get('shop_id');
    }

    /**
     * Get's a shop appearance
     *
     * @param int
     * @return collection
     */
    public function get()
    {

        $appearance = ForumAppearance::where('shop_id', $this->shop_id)->first();

        return $appearance;

    }


    /**
     * Updates a shop appearance.
     *
     * @param int
     * @param array
     * @return
     */
    public function update($request)
    {

        $appearance = ForumAppearance::where('shop_id',$this->shop_id)->first();

        $appearance->shop_id = $this->shop_id;
        $appearance->primary_button_color = $request->primary_button_color;
        $appearance->link_color = $request->link_color;
        $appearance->heading_color = $request->heading_color;
        $appearance->paragraph_color = $request->paragraph_color;

        $appearance->save();

        return $appearance;
    }

    /**
     * Store a shop appearance.
     *
     * @param $request
     * @return ForumAppearance
     */
    public function store($request)
    {

        $appearance = new ForumAppearance;

        $appearance->shop_id = $request->id;
        $appearance->primary_button_color = $this->default_primary_button_color;
        $appearance->link_color = $this->default_link_color;
        $appearance->heading_color = $this->default_heading_color;
        $appearance->paragraph_color = $this->default_paragraph_color;

        $appearance->save();

        return $appearance;

    }
}
