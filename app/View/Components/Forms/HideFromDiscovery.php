<?php

namespace App\View\Components\Forms;

use App\Models\Entity;
use Illuminate\View\Component;

class HideFromDiscovery extends Component
{
    public $entity;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.hide-from-discovery');
    }
}
