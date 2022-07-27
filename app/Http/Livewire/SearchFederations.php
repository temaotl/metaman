<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Federation;
use Illuminate\Support\Facades\Auth;

class SearchFederations extends Component
{
    public $search = '';
    public $queryString = ['search' => ['except' => '']];

    public function render()
    {
        $federations = Federation::query()
            ->visibleTo(Auth::user())
            ->search($this->search)
            ->orderByDesc('active')
            ->orderByDesc('approved')
            ->orderBy('name')
            ->paginate();

        return view('livewire.search-federations', [
            'federations' => $federations,
        ]);
    }
}
