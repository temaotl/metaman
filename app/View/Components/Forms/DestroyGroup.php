<?php

namespace App\View\Components\Forms;

use App\Models\Group;
use Illuminate\View\Component;

class DestroyGroup extends Component
{
    public $group;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.destroy-group');
    }
}
