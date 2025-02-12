<?php

namespace App\Livewire;

use Livewire\Component;
use App\Libraries\DeepSeek as DeepSeekLibrary;

class DeepSeek extends Component
{

    public $prompt = '';

    public $question = '';

    public $answer = '';


    protected $deepSeekLibrary;

    public function boot()
    {
        $this->deepSeekLibrary = new DeepSeekLibrary();
    }


    function submitPrompt()
    {
        $this->question = $this->prompt;

        if ($this->question == '') {
            return;
        }

        $this->prompt = '';

        $this->js('$wire.ask()');
    }

    function ask()
    {
        $this->answer = $this->deepSeekLibrary->ask($this->question, function ($partial) {
            $this->stream(to: 'answer', content: $partial);
        });
    }
 


    public function render()
    {
        return view('livewire.deep-seek');
    }
}
