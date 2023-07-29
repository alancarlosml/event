<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class EventLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('layouts.event');
    }
}
