<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ trans('general.assigned_to', ['name' => $show_user->present()->fullName()]) }} - {{ date('Y-m-d H:i', time()) }}</title>

    <link rel="shortcut icon" type="image/ico" href="{{ ($snipeSettings) && ($snipeSettings->favicon!='') ?  Storage::disk('public')->url(e($snipeSettings->favicon)) : config('app.url').'/favicon.ico' }}">

    {{-- stylesheets --}}
    <link rel="stylesheet" href="{{ url(mix('css/dist/all.css')) }}">

    <script nonce="{{ csrf_token() }}">
        window.snipeit = {
            settings: {
                "per_page": 50
            }
        };
    </script>

    <style>
        body {
            font-family: "Arial, Helvetica", sans-serif;
            padding: 20px;
        }
        table.inventory {
            width: 100%;
            border: 1px solid #d3d3d3;
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

    <script nonce="{{ csrf_token() }}">
        window.snipeit = {
            settings: {
                "per_page": 50
            }
        };
    </script>

</head>
<body>

@if ($snipeSettings->logo_print_assets=='1')
	<h1>
	    Ausleihbestätigung
	</h1>
	<img class="print-logo" src="{{ config('app.url') }}/uploads/{{ $snipeSettings->logo }}">
@endif

<h3>
    {{ trans('general.assigned_to', ['name' => $show_user->present()->fullName()]) }}
    {{ ($show_user->employee_num!='') ? ' (#'.$show_user->employee_num.') ' : '' }}
    {{ ($show_user->jobtitle!='' ? ' - '.$show_user->jobtitle : '') }}
</h3>
<p></p>{{ trans('admin/users/general.all_assigned_list_generation')}} {{ Helper::getFormattedDateObject(now(), 'datetime', false) }}</body>
    @if ($assets->count() > 0)
        @php
	    $counter = 1;
	@endphp
        <table class="inventory">
            <thead>
                <th data-field="asset_id" data-sortable="false" data-visible="true" data-switchable="false">#</th>
                <th data-field="asset_image" data-sortable="true" data-visible="false" data-switchable="true">{{ trans('general.image') }}</th>
                <th data-field="asset_tag" data-sortable="true" data-visible="true" data-switchable="false">{{ trans('admin/hardware/table.asset_tag') }}</th>
                <th data-field="asset_name" data-sortable="true" data-visible="true">{{ trans('general.name') }}</th>
                <th data-field="asset_category" data-sortable="true" data-visible="true">{{ trans('general.category') }}</th>
                <th data-field="asset_model" data-sortable="true" data-visible="true">{{ trans('admin/hardware/form.model') }}</th>
                <th data-field="rtd_location" data-sortable="true" data-visible="true">{{ trans('admin/hardware/form.default_location') }}</th>
                <th data-field="asset_location" data-sortable="true" data-visible="false">{{ trans('general.location') }}</th>
                <th data-field="asset_serial" data-sortable="true" data-visible="true">{{ trans('admin/hardware/form.serial') }}</th>
                <th data-field="asset_checkout_date" data-sortable="true" data-visible="true">{{ trans('admin/hardware/table.checkout_date') }}</th>
                <th data-field="signature" data-sortable="true" data-visible="true">{{ trans('general.signature') }}</th>
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
                    <td>
                        @if ($asset->getImageUrl())
                            <img src="{{ $asset->getImageUrl() }}" class="thumbnail" style="max-height: 50px;">
                        @endif
                    </td>
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
                @if ($settings->show_assigned_assets)
                    @php
                        $assignedCounter = 1;
                    @endphp
                    @foreach ($asset->assignedAssets as $asset)

                        <tr>
                            <td>{{ $counter }}.{{ $assignedCounter }}</td>
                            <td data-formatter="imageFormatter">
                                @if ($asset->getImageUrl())
                                    <img src="{{ $asset->getImageUrl() }}" class="thumbnail" style="max-height: 50px;">
                                @endif
                            </td>
                            <td>{{ $asset->asset_tag }}</td>
                            <td>{{ $asset->name }}</td>
                            <td>{{ $asset->model->category->name }}</td>
                            <td>{{ ($asset->defaultLoc) ? $asset->defaultLoc->name : '' }}</td>
                            <td>{{ ($asset->location) ? $asset->location->name : '' }}</td>
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
            </tbody>
        </table>
    @endif

    @if ($licenses->count() > 0)
        <div id="licenses-toolbar">
            <h4>{{ trans_choice('general.countable.licenses', $licenses->count(), ['count' => $licenses->count()]) }}</h4>
        </div>

        <table
                class="snipe-table table table-striped inventory"
                id="licensessAssigned"
                data-toolbar="#licenses-toolbar"
                data-pagination="false"
                data-id-table="licensessAssigned"
                data-search="false"
                data-side-pagination="client"
                data-sortable="true"
                data-show-columns="true"
                data-sort-order="desc"
                data-sort-name="created_at"
                data-show-columns-toggle-all="true"
                data-cookie-id-table="licensessAssigned">
            <thead>
            <tr>
                <th style="width: 20px;" data-sortable="false" data-switchable="false">#</th>
                <th style="width: 40%;" data-sortable="true" data-switchable="false">{{ trans('general.name') }}</th>
                <th style="width: 50%;" data-sortable="true">{{ trans('admin/licenses/form.license_key') }}</th>
                <th style="width: 10%;" data-sortable="true">{{ trans('admin/hardware/table.checkout_date') }}</th>
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
                    <td>{{  $license->pivot->updated_at }}</td>
                </tr>
                @php
                    $lcounter++
                @endphp
            @endforeach
        </table>
    @endif


    @if ($accessories->count() > 0)
        <div id="accessories-toolbar">
            <h4>{{ trans_choice('general.countable.accessories', $accessories->count(), ['count' => $accessories->count()]) }}</h4>
        </div>

        <table
                class="snipe-table table table-striped inventory"
                id="accessoriesAssigned"
                data-toolbar="#accessories-toolbar"
                data-pagination="false"
                data-id-table="accessoriesAssigned"
                data-search="false"
                data-side-pagination="client"
                data-sortable="true"
                data-show-columns="true"
                data-sort-order="desc"
                data-sort-name="created_at"
                data-show-columns-toggle-all="true"
                data-cookie-id-table="accessoriesAssigned">
            <thead>
            <tr>
                <th style="width: 20px;" data-sortable="false" data-switchable="false">#</th>
                <th data-field="accessory_image" data-sortable="true"  data-visible="true">{{ trans('general.image') }}</th>
                <th style="width: 40%;" data-sortable="true" data-switchable="false">{{ trans('general.name') }}</th>
                <th style="width: 50%;" data-sortable="true">{{ trans('general.category') }}</th>
                <th style="width: 10%;" data-sortable="true">{{ trans('admin/hardware/table.checkout_date') }}</th>
                <th style="width: 10%;" data-sortable="true">{{ trans('general.signature') }}</th>
            </tr>
            </thead>
            @php
                $acounter = 1;
            @endphp

            @foreach ($accessories as $accessory)
                @if ($accessory)
                    <tr>
                        <td>{{ $acounter }}</td>
                        <td>
                            @if ($accessory->getImageUrl())
                                <img src="{{ $accessory->getImageUrl() }}" class="thumbnail" style="max-height: 50px;">
                            @endif
                        </td>
                        <td>{{ ($accessory->manufacturer) ? $accessory->manufacturer->name : '' }} {{ $accessory->name }} {{ $accessory->model_number }}</td>
                        <td>{{ $accessory->category->name }}</td>
                        <td>{{ $accessory->pivot->created_at }}</td>

                        <td>
                            @if (($accessory->assetlog->first()) && ($accessory->assetlog->first()->accept_signature!=''))
                            <img style="width:auto;height:100px;" src="{{ asset('/') }}display-sig/{{ $accessory->assetlog->first()->accept_signature }}">
                            @endif
                        </td>
                    </tr>
                    @php
                        $acounter++
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
