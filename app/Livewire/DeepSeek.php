<?php

namespace App\Livewire;

use Livewire\Component;
use App\Libraries\DeepSeek as DeepSeekLibrary;
use Illuminate\Support\Facades\Log;
use DeepSeek\DeepSeekClient;
use DeepSeek\Enums\Models;


class DeepSeek extends Component
{

    public $prompt = '';

    public $question = '';

    public $originalAnswer = '';

    public $answer = '';

    public $answerIng = false;

    public $usage = null;

    public $currentHasAnswer = false;

    public $dialogs = [];

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

        $this->dialogs[] = [
            'role' => 'user',
            'content' => $this->question,
        ];

        $this->answerIng = true;
        $this->js('$wire.ask()');
    }


    function ask()
    {
        set_time_limit(120);

        $this->deepSeekLibrary->ask($this->question, function ($chunk) {
            Log::info('chunk: ' . json_encode($chunk, JSON_UNESCAPED_UNICODE));

            $content = $chunk['choices'][0]['delta']['content'] ?? '';

            if ($content) {
                $this->originalAnswer = $this->originalAnswer . $content;

                // 推送 SSE 到前端, 追加模式
                // $this->answer = $this->originalAnswer;
                // $replace = $this->currentHasAnswer == false ? true : false;     // 只有第一次 回答产生内容时，才替换
                // $this->stream(to: 'answer', content: $content, replace: $replace);      // 回答 stream 中首次出现内容时，替换，后续则追加


                // 转 markdown 替换模式
                $this->answer = $this->originalAnswer;
                // $this->answer = app(\Spatie\LaravelMarkdown\MarkdownRenderer::class)->toHtml($this->originalAnswer);
                $this->stream(to: 'answer', content: $this->answer, replace: true);      // 回答 stream 中首次出现内容时，替换，后续则追加

                // 本次回答产生内容
                $this->currentHasAnswer = true;
            }

            if ($chunk['choices'][0]['finish_reason'] == 'stop' && isset($chunk['usage'])) {
                $this->usage = $chunk['usage'];
            }
        });

        Log::info('stream_end?: ' . $this->answer);

        $this->dialogs[] = [
            'role' => 'system',
            'content' => $this->answer,
        ];

        $this->currentHasAnswer = false;
        $this->usage = null;
        $this->originalAnswer = '';
        $this->answer = '';
        $this->answerIng = false;
    }
 


    public function render()
    {
        return view('livewire.deep-seek');
    }
}
