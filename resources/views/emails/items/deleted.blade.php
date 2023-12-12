<x-mail::message>
# Item Deleted


<p>Item details: </p>
<div>
    Name: {{ $item->name }} <br>
    Description: {{ $item->description }} <br>
    Price: {{ $item->description }} <br>
    Quantity: {{ $item->description }} <br>
    Created at: {{ $item->created_at }} <br>
    Last updated at: {{ $item->updated_at }} <br>
    Deleted at: {{ date('Y-m-d H:i:s') }} <br>
</div>
<br>
<!-- <x-mail::button :url="''">
Button Text
</x-mail::button> -->

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
