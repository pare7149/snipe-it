<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ trans('general.assigned_to', ['name' => $show_user->present()->fullName()]) }} - {{ date('Y-m-d H:i', time()) }}</title>
    <style>
        body {
            font-family: "Arial, Helvetica", sans-serif;
        }
        table.inventory {
            border: solid #000;
            border-width: 1px 1px 1px 1px;
            width: 100%;
        }

        @page {
            size: A4;
	}
	
        table.inventory th, table.inventory td {
	    border: solid #000;
            border-width: 0 1px 1px 0;
            padding: 6px;
            font-size: 22px;
        }

	.print-logo {
	    height:80px;
	    position: absolute;
	    right: 20px;
	    top: 20px;
	}

	p {
	    font-size: 18px;
	}
	
	.signature td {
		font-size:18px;
	}
    </style>
</head>
<body>

@if ($snipeSettings->logo_print_assets=='1')
	<h1>
	    Ausleihbestätigung
	</h1>
	<img class="print-logo" src="{{ config('app.url') }}/uploads/{{ $snipeSettings->logo }}">
@endif

<h3>{{ trans('general.assigned_to', ['name' => $show_user->present()->fullName()]) }} {{ ($show_user->jobtitle!='' ? ' - '.$show_user->jobtitle : '') }}
</h3>
    @if ($assets->count() > 0)
        @php
	    $counter = 1;
	@endphp
        <table class="inventory">
            <thead>
            <tr>
                <th colspan="8">{{ trans('general.assets') }}</th>
            </tr>
            </thead>
            <thead>
            <tr>
                <th style="width: 20px;"></th>
                <th style="width: 20%;">Inventarnummer</th>
                <th style="width: 20%;">{{ trans('general.name') }}</th>
		<th style="width: 10%;">Ausgabe am</th>
		<th style="width: 10%;">Rückgabe bis</th>
		<th style="width: 40%;">Notizen</th>
            </tr>
            </thead>

            @foreach ($assets as $asset)
	    	@php
		    $checkout_date_object = date_create_from_format("Y-m-d H:i:s", $asset->last_checkout); 
		    $checkin_date_object = date_create_from_format("Y-m-d H:i:s", $asset->expected_checkin);
		@endphp
                <tr>
                    <td>{{ $counter }}</td>
                    <td>{{ $asset->asset_tag }}</td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->last_checkout }}</td>
                    <td>{{ $asset->expected_checkin }}</td>
                    <td>{{ $asset->notes }}</td>
                        @if (($asset->assetlog->first()) && ($asset->assetlog->first()->accept_signature!=''))
                            <img style="width:auto;height:100px;" src="{{ asset('/') }}display-sig/{{ $asset->assetlog->first()->accept_signature }}">
                        @endif
                    </td>
                </tr>
                @if($settings->show_assigned_assets)
                    @php
                        $assignedCounter = 1;
                    @endphp
                    @foreach ($asset->assignedAssets as $asset)

                        <tr>
                            <td>{{ $counter }}.{{ $assignedCounter }}</td>
                            <td>{{ $asset->asset_tag }}</td>
                            <td>{{ $asset->name }}</td>
                            <td>{{ $asset->model->category->name }}</td>
                            <td>{{ $asset->model->name }}</td>
                            <td>{{ $asset->serial }}</td>
                            <td>{{ $asset->last_checkout }}</td>
                            <td><img style="width:auto;height:100px;" src="{{ asset('/') }}display-sig/{{ $asset->assetlog->first()->accept_signature }}"></td>
                        </tr>
                        @php
                            $assignedCounter++
                        @endphp
                    @endforeach
                @endif
                @php
                    $counter++
                @endphp
            @endforeach
        </table>
    @endif

    @if ($licenses->count() > 0)
        <br><br>
        <table class="inventory">
            <thead>
            <tr>
                <th colspan="4">{{ trans('general.licenses') }}</th>
            </tr>
            </thead>
            <thead>
            <tr>
                <th style="width: 20px;"></th>
                <th style="width: 40%;">{{ trans('general.name') }}</th>
                <th style="width: 50%;">{{ trans('admin/licenses/form.license_key') }}</th>
                <th style="width: 10%;">{{ trans('admin/hardware/table.checkout_date') }}</th>
            </tr>
            </thead>
            @php
                $lcounter = 1;
            @endphp

            @foreach ($licenses as $license)

                <tr>
                    <td>{{ $lcounter }}</td>
                    <td>{{ $license->name }}</td>
                    <td>
                        @can('viewKeys', $license)
                            {{ $license->serial }}
                        @else
                            <i class="fa-lock" aria-hidden="true"></i> {{ str_repeat('x', 15) }}
                        @endcan
                    </td>
                    <td>{{  $license->pivot->created_at }}</td>
                </tr>
                @php
                    $lcounter++
                @endphp
            @endforeach
        </table>
    @endif


    @if ($accessories->count() > 0)
        <br><br>
        <table class="inventory">
            <thead>
            <tr>
                <th colspan="4">{{ trans('general.accessories') }}</th>
            </tr>
            </thead>
            <thead>
            <tr>
                <th style="width: 20px;"></th>
                <th style="width: 40%;">{{ trans('general.name') }}</th>
                <th style="width: 50%;">{{ trans('general.category') }}</th>
                <th style="width: 10%;">{{ trans('admin/hardware/table.checkout_date') }}</th>
            </tr>
            </thead>
            @php
                $acounter = 1;
            @endphp

            @foreach ($accessories as $accessory)
                @if ($accessory)
                    <tr>
                        <td>{{ $acounter }}</td>
                        <td>{{ ($accessory->manufacturer) ? $accessory->manufacturer->name : '' }} {{ $accessory->name }} {{ $accessory->model_number }}</td>
                        <td>{{ $accessory->category->name }}</td>
                        <td>{{ $accessory->pivot->created_at }}</td>
                    </tr>
                    @php
                        $acounter++
                    @endphp
                @endif
            @endforeach
        </table>
    @endif

    @if ($consumables->count() > 0)
        <br><br>
        <table class="inventory">
            <thead>
            <tr>
                <th colspan="4">{{ trans('general.consumables') }}</th>
            </tr>
            </thead>
            <thead>
            <tr>
                <th style="width: 20px;"></th>
                <th style="width: 40%;">{{ trans('general.name') }}</th>
                <th style="width: 50%;">{{ trans('general.category') }}</th>
                <th style="width: 10%;">{{ trans('admin/hardware/table.checkout_date') }}</th>
            </tr>
            </thead>
            @php
                $ccounter = 1;
            @endphp

            @foreach ($consumables as $consumable)
                @if ($consumable)
                    <tr>
                        <td>{{ $ccounter }}</td>


                        <td>
                        @if ($consumable->deleted_at!='')
                            <td>{{ ($consumable->manufacturer) ? $consumable->manufacturer->name : '' }}  {{ $consumable->name }} {{ $consumable->model_number }}</td>
                            @else
                            {{ ($consumable->manufacturer) ? $consumable->manufacturer->name : '' }}  {{ $consumable->name }} {{ $consumable->model_number }}
                            @endif
                            </td>
                            <td>{{ ($consumable->category) ? $consumable->category->name : ' invalid/deleted category' }} </td>
                            <td>{{  $consumable->pivot->created_at }}</td>
                    </tr>
                    @php
                        $ccounter++
                    @endphp
                @endif
            @endforeach
        </table>
    @endif
        <div>
	    <br>
	    <br>
		<h3>Die Rückgabefristen finden Sie neben dem Medium aufgelistet. Die Medien sind bis zur angegebenen Rückgabefrist zurückzugeben!</h3>
		<h3>Der Entleiher haftet persöhnlich für den entliehenen Gegenstand. Mit ihrer Unterschrift bestätigen Sie die Übernahme der oben aufgeführten Hardware.</h3>
	    <br>
	    <br>
	    <br>
	    <br>
		<p>Erstellt am: {{ Helper::getFormattedDateObject(now(), 'datetime', false) }}</p>
	    <br>
	    <br>
	    <table class="signature">
	        <tr>
	            <td>Unterschrift:</td>
	            <td>__________________________________________</td>
	            <td></td>
	            <td>{{ trans('general.date') }}:</td>
	            <td>__________________________________________</td>
	        </tr>
	    </table>
        </div>
</body>
</html>
