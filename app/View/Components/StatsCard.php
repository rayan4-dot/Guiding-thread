<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatsCard extends Component
{
    public $color;
    public $icon;
    public $title;
    public $value;
    public $description;
    /**
     * Create a new component instance.
     */
    public function __construct($color, $icon, $title, $value, $description)
    {
        $this->color = $color;
        $this->icon = $icon;
        $this->title = $title;
        $this->value = $value;
        $this->description = $description;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.stats-card');
    }
}
