<x-mail::message>
# Introduction

New category created.

<div>
    Name: {{ $category->name }} <br>
    Description: {{ $category->description }} <br>
</div>

#<x-mail::button :url="''">
#Button Text
#</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
