<?php

namespace App\View\Components\Forms;

use App\Models\Membership;
use Illuminate\View\Component;

class MembershipAccept extends Component
{
    public $membership;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Membership $membership)
    {
        $this->membership = $membership;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.forms.membership-accept');
    }
}
