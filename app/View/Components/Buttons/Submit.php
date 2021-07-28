<?php

namespace App\View\Components\Buttons;

use Illuminate\View\Component;

class Submit extends Component
{
    public $text;
    public $color;
    public $target;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $text, string $color = 'blue', string $target = null)
    {
        $this->text = $text;
        $this->color = $color;
        $this->target = $target;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.buttons.submit');
    }
}
