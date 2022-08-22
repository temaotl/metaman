<?php

namespace App\View\Components\Forms;

use App\Models\User;
use Illuminate\View\Component;

class ChangeUserStatus extends Component
{
    public $route;

    public $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $route, User $user)
    {
        $this->route = $route;
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.forms.change-user-status');
    }
}
