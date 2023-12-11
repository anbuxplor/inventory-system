<x-mail::message>
# Introduction

<div>New item created. </div>
<div>
    Name: {{ $item->name }} <br>
    Description: {{ $item->description }} <br>
    Price: {{ $item->description }} <br>
    Quantity: {{ $item->description }} <br>
    Created at: {{ $item->created_at }} <br>
</div>

#<x-mail::button :url="''">
#Button Text
#</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
