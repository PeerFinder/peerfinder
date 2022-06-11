@props(['infocards' => null])

@if ($infocards && count($infocards))
<div class="my-5 space-y-2">
@foreach ($infocards as $infocard)
    <infocard title="{{ $infocard->title }}"
        close-url="{{ route('infocards.close', ['slug' => $infocard->slug]) }}" 
        close-caption="{{ __('infocards.dismiss_button') }}"
        :closable="{{ $infocard->closable }}">{!! Pages::markdown($infocard->body) !!}</infocard>
@endforeach
</div>
@endif