@foreach($conversationList as $key)
    @if($key->from_user_id === Auth::user()->id)
        <div class="text-right">
            <p>{{ $key->chat_message }}</p>
        </div>
    @else
        <div class="text-left">
            <p>{{ $key->chat_message }}</p>
        </div>
    @endif
@endforeach
