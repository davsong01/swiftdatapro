<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BalanceToggle extends Component
{
    /**
     * Create a new component instance.
     */
    public $balance;

    public function __construct($balance)
    {
        $this->balance = $balance;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.balance-toggle');
    }
}
