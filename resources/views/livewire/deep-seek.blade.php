<div>
    <section>
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
    </form>
</div>
