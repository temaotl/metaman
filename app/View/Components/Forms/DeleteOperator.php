<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class DeleteOperator extends Component
{
    public $route;

    public $model;

    public $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $route, $model, int $user)
    {
        $this->route = $route;
        $this->model = $model;
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.forms.delete-operator');
    }
}
