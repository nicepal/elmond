<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchForm extends Component
{
    public $placeholder;
    public $name;
    public $value;
    public $method;
    public $action;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($placeholder = 'Search...', $name = 'search', $value = null, $method = 'GET', $action = null)
    {
        $this->placeholder = $placeholder;
        $this->name = $name;
        $this->value = $value ?? request($name);
        $this->method = $method;
        $this->action = $action;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.search-form');
    }
}