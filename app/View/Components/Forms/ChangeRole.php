<?php

namespace App\View\Components\Forms;

use App\Models\User;
use Illuminate\View\Component;

class ChangeRole extends Component
{
    public $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.forms.change-role');
    }
}
