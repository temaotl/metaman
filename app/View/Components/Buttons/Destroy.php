<?php

namespace App\View\Components\Buttons;

use Illuminate\View\Component;

class Destroy extends Component
{
    public $target;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $target)
    {
        $this->target = $target;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.buttons.destroy');
    }
}
