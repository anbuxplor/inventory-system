<x-mail::message>
@if($item->is_created)
# New item created.
@else
# Item updated 
@endif

<p>Item details: </p>
<div>
    Name: {{ $item->name }} <br>
    Description: {{ $item->description }} <br>
    Price: {{ $item->description }} <br>
    Quantity: {{ $item->description }} <br>
    Created at: {{ $item->created_at }} <br>
    Updated at: {{ $item->updated_at }} <br>
</div>

<br>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
