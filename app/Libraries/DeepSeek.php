<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

class DeepSeek
{

    protected $apiKey = 'sk-09287e41eb76486ca6bfa9ebb3657d76';

    protected $baseUrl = 'https://api.deepseek.com/';



    public function ask($question, $callback = null)
    {
        $messages = [
            [
                'role' => 'user',
                'content' => $question,
            ]
        ];

        $client = new Client();
        $response = $client->post($this->baseUrl . 'chat/completions', [
            'connect_timeout' => 120,
            'timeout' => 120,
            'stream' => true,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                // 'Accept'  => 'application/json',
                // 'Accept' => 'text/event-stream',
                // 'Content-Type'  => 'application/json',
            ],
            'json' => [
                'messages' => $messages,
                "model" => "deepseek-chat",
                // "model" => "deepseek-reasoner",

                // frequency_penalty 说明：较高的frequency_penalty值会使模型更倾向于使用新的词汇，降低重复度；而较低的值则可能导致模型生成更多重复内容。
                // 在文本生成过程中，模型会根据上下文预测下一个最可能出现的词汇。frequency_penalty通过调整词汇的概率分布
                // 使得已经频繁出现的词汇在未来生成中出现的概率降低。这种机制不仅能减少冗余，提高文本的可读性，还能激发模型生成更加多样化和富有创意的内容
                "frequency_penalty" => 0,       // >= -2 and <= 2   default: 0

                // max_tokens 限制一次请求中模型生成 completion 的最大 token 数。输入 token 和输出 token 的总长度受模型的上下文长度的限制。
                "max_tokens" => 2048,             // >= 1 and <= 8192  default: 4096

                // presence_penalty 说明：presence_penalty 用于调整模型生成的文本中词汇的多样性。较高的值会使模型生成更多不同的词汇，
                // 而较低的值则可能导致模型生成更多重复内容。
                "presence_penalty" => 0,            // >= -2 and <= 2   default: 0


                // 返回的格式       
                // "response_format" => [       // 这个设置了会报错，不知道为何
                //     "type" => "json_object"        // text, json_object    default: text
                // ],

                // 一个 string 或最多包含 16 个 string 的 list 在遇到这些词时，API 将停止生成更多的 token
                "stop" => null,


                "stream" => true,  // 是否流式输出
                // "stream_options" => [
                //     // 是否返回使用情况 在流式消息最后的 data: [DONE] 之前将会传输一个额外的块。此块上的 usage 字段显示整个请求的 token 使用统计信息
                //     'include_usage' => true,
                // ],
                

                // 更高的值，如 0.8，会使输出更随机，而更低的值，如 0.2，会使其更加集中和确定
                "temperature" => 1.3,     // >= 0 and <= 2   default: 1
                // temperature 和 top_p 只建议修改其中一个
                // "top_p" => 1,           // >= 0 and <= 1   default: 1


                // "tools" => null,
                // "tools" => [            // default: null
                //     [
                //         'type' => 'function',
                //         'function' => [
                //             'description' => 'This tool will allow you to ask a question and get an answer.',
                //             'name' => 'qa',
                //             'parameters' => [       // 参数

                //             ]
                //         ]
                //     ]
                // ],

                // 控制模型调用 tool 的行为
                // "tool_choice" => "none",        // none：不调用任何 tool；auto：意味着模型可以选择生成一条消息或调用一个或多个 tool；required：模型必须调用一个或多个 tool


                // "logprobs" => true,      // 是否返回所输出 token 的对数概率  default: false
                // "top_logprobs" => 5        // <= 20   logprobs 为 true 时，返回的对数概率的数量
            ],
        ]);

        // 逐行读取流式响应并输出
        $stream = $response->getBody();
        // while (!$stream->eof()) {
        //     $chunk = $stream->read(1024);
        //     $callback($chunk);
        // }



        while (! $stream->eof()) {
            $line = $this->readLine($stream);

            $event = null;
            // if (str_starts_with($line, 'event:')) {
            //     $event = trim(substr($line, strlen('event:')));
            //     $line = $this->readLine($this->response->getBody());
            // }

            if (! str_starts_with($line, 'data:')) {
                continue;
            }

            $data = trim(substr($line, strlen('data:')));

            if ($data === '[DONE]') {
                break;
            }

            /** @var array{error?: array{message: string|array<int, string>, type: string, code: string}} $response */
            $json_message = json_decode($data, true, flags: JSON_THROW_ON_ERROR);

            if (isset($json_message['error'])) {
                throw new \Exception($json_message['error']);
            }


            $callback($json_message);

            // if ($event !== null) {
            //     $response['__event'] = $event;
            //     $response['__meta'] = $this->meta();
            // }

            // yield $this->responseClass::from($response);
        }

    }


    private function readLine(StreamInterface $stream): string
    {
        $buffer = '';

        while (! $stream->eof()) {
            if ('' === ($byte = $stream->read(1))) {
                return $buffer;
            }
            $buffer .= $byte;
            if ($byte === "\n") {
                break;
            }
        }

        return $buffer;
    }
}
