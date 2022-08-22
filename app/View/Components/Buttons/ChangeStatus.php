<?php

namespace App\View\Components\Buttons;

use Illuminate\View\Component;

class ChangeStatus extends Component
{
    public $model;

    public $target;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($model, string $target)
    {
        $this->model = $model;
        $this->target = $target;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.buttons.change-status');
    }
}
