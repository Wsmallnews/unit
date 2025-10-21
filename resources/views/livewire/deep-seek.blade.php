<div>
    {{-- <section>
        <div>ChatBot</div>

        @if ($question)
            <article>
                <hgroup>
                    <h3>User</h3>
                    <p>{{ $question }}</p>
                </hgroup>

                <hgroup>
                    <h3>ChatBot</h3>
                    <p wire:stream="answer">{{ $answer }}</p> 
                </hgroup>
            </article>
        @endif
    </section>

    <form wire:submit="submitPrompt">
        <input wire:model="prompt" type="text" placeholder="Send a message" autofocus>

        <button type="submit">Send</button>
    </form> --}}

    <!-- 顶部导航栏 -->
    <header class="bg-white shadow-sm py-3 px-4 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-deepseek-primary rounded-full flex items-center justify-center">
                <i class="fas fa-robot text-white"></i>
            </div>
            <h1 class="text-lg font-semibold text-gray-800">DeepSeek Chat</h1>
        </div>
        <div class="flex items-center space-x-4">
            <button class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-history"></i>
            </button>
            <button class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-cog"></i>
            </button>
        </div>
    </header>

    <!-- 对话内容区域 -->
    <main class="flex-1 overflow-y-auto p-4 space-y-6">

        @foreach ($dialogs as $dialog)
            @if ($dialog['role'] == 'user')
                <!-- 用户消息 -->
                <div class="flex justify-end">
                    <div class="max-w-3xl w-full flex space-x-3">
                        <div class="flex-1"></div>
                        <div class="bg-deepseek-primary text-white p-4 rounded-lg rounded-tr-none shadow">
                            <p>{!! $dialog['content'] !!}</p>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-user text-gray-600"></i>
                        </div>
                    </div>
                </div>
            @endif

            @if ($dialog['role'] == 'system')
                <!-- AI 回复 -->
                <div class="flex justify-start">
                    <div class="max-w-3xl w-full flex space-x-3">
                        <div class="w-8 h-8 rounded-full bg-deepseek-primary flex items-center justify-center">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                        <div class="bg-white p-4 rounded-lg rounded-tl-none shadow flex-1">
                            <p>{!! $dialog['content'] !!}</p>
                            <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-start space-x-4 text-gray-500">
                                <button class="hover:text-deepseek-primary">
                                    <i class="far fa-thumbs-up"></i>
                                </button>
                                <button class="hover:text-deepseek-primary">
                                    <i class="far fa-thumbs-down"></i>
                                </button>
                                <button class="hover:text-deepseek-primary">
                                    <i class="far fa-copy"></i>
                                </button>
                                <button class="hover:text-deepseek-primary">
                                    <i class="fas fa-share"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        @if ($answerIng)
            <div class="flex justify-start">
                <div class="max-w-3xl w-full flex space-x-3">
                    <div class="w-8 h-8 rounded-full bg-deepseek-primary flex items-center justify-center">
                        <i class="fas fa-robot text-white"></i>
                    </div>
                    <div class="bg-white p-4 rounded-lg rounded-tl-none shadow flex-1">
                        <p wire:stream="answer">
                            <i class="fas fa-history"></i>
                            正在思考中...
                        </p>
                        {{-- <div class="flex gap-1">
                            三个跳动的点的动画，需要测试
                            <span class="size-1.5 rounded-full bg-on-surface motion-safe:animate-[bounce_1s_ease-in-out_infinite] dark:bg-on-surface-dark"></span>
                            <span class="size-1.5 rounded-full bg-on-surface motion-safe:animate-[bounce_0.5s_ease-in-out_infinite] dark:bg-on-surface-dark"></span>
                            <span class="size-1.5 rounded-full bg-on-surface motion-safe:animate-[bounce_1s_ease-in-out_infinite] dark:bg-on-surface-dark"></span>
                        </div> --}}
                    </div>
                </div>
            </div>
        @endif
    </main>

    <!-- 输入框区域 -->
    <footer class="bg-white border-t border-gray-200 p-4">
        <div class="max-w-3xl mx-auto">
            <div class="relative">
                <form wire:submit="submitPrompt">
                    <textarea 
                        wire:model="prompt"
                        class="w-full border border-gray-300 rounded-lg py-3 px-4 pr-12 focus:outline-none focus:ring-2 focus:ring-deepseek-primary focus:border-transparent resize-none"
                        rows="1"
                        placeholder="输入消息..."
                    ></textarea>
                    <button class="absolute right-3 bottom-3 text-gray-500 hover:text-deepseek-primary" type="submit">
                        <i class="fas fa-paper-plane text-lg"></i>
                    </button>
                </form>
            </div>

            <div class="mt-2 text-xs text-gray-500 text-center">
                <p>DeepSeek Chat 可以产生错误信息，请谨慎验证。</p>
            </div>
        </div>
    </footer>
</div>

@assets
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    {{-- markdown --}}
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>

    {{-- <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.x/dist/typography.min.css" rel="stylesheet"> --}}
    {{-- markdown --}}


    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        deepseek: {
                            primary: '#1e40af',
                            secondary: '#1e3a8a',
                            light: '#eff6ff',
                        }
                    }
                }
            }
        }
    </script>

    <script>
        // 自动调整文本输入框高度
        // const textarea = document.querySelector('textarea');
        // textarea.addEventListener('input', function() {
        //     this.style.height = 'auto';
        //     this.style.height = (this.scrollHeight) + 'px';
        // });

        // setTimeout(() => {
        //     console.log('settimeoutn');
        //     Livewire.on('stream', (event) => {
        //         console.log('aaaaaaaaaaaaaaaaaaaaaaaaaaaaa', event);
        //     });
        // }, 1000)
    </script>
@endassets