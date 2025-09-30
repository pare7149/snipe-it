@component('mail::message')

## Assets Überzogen     
Folgende Assets wurden überzogen:

@component('mail::table')

@if ($assets->count() > 0)

## {{ $assets->count() }} {{ trans('general.assets') }}

<table width="100%">
    <tr>
        <th></th> 
        <th align="left">{{ trans('mail.name') }} </th>
        <th align="left">{{ trans('mail.asset_tag') }}</th>
        <th align="left">Verliehen an</th>
        <th align="left">Ausleihdatum</th>
        <th align="left">Rückgabedatum</th>

</tr>


@foreach($assets as $asset)
<tr>
    @if (($snipeSettings->show_images_in_email =='1') && $asset->getImageUrl())
        <td>
            <img width=32 height=32 src="{{ asset($asset->getImageUrl()) }}" alt="Asset" style="max-width: 64px;">
        </td>
    @else
        <td></td>
    @endif
    <td><a href="{{ url("/hardware/" .$asset->id) }}">{{ $asset->present()->name }}</a></td>
    <td><a href="{{ url("/hardware/" .$asset->id) }}">{{ $asset->present()->asset_tag }}</a></td>
    <td>
        @if ($asset->assignedTo)
            <a href="{{ url("/users/" . $asset->assigned_to) }}">
                @if ($asset->assignedTo->first_name)
                    {{$asset->assignedTo->first_name}} 
                @endif
                @if ($asset->assignedTo->last_name)
                    {{$asset->assignedTo->last_name}}
                @endif
            </a>
        @endif
    </td>
    <td>{{ $asset->last_checkout }}</td>
    <td>{{ $asset->expected_checkin}}</td>

</tr>
@endforeach
</table>
@endif

@endcomponent


@endcomponent
