<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Cancel extends Component
{
    public $route;
    public $model;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $route, $model)
    {
        $this->route = $route;
        $this->model = $model;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.forms.cancel');
    }
}
