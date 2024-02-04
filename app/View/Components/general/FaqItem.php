<?php

namespace App\View\Components\general;

use Illuminate\View\Component;

class FaqItem extends Component
{
    /**
     * Faq Item.
     * 
     * doc: for the front end
     *
     * @return void
     */

     public $question;
     public $answer;

    public function __construct($question , $answer)
    {
        $this->question = $question;
        $this->answer = $answer;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.general.faq-item');
    }
}
