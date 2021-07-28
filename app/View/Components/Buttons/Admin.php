<?php

namespace App\View\Components\Buttons;

use App\Models\User;
use Illuminate\View\Component;

class Admin extends Component
{
    public $user;
    public $target;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($user, string $target)
    {
        $this->user = $user;
        $this->target = $target;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.buttons.admin');
    }
}
