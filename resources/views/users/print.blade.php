<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @if ((isset($users) && count($users) === 1))
        <title>{{ trans('general.assigned_to', ['name' => $users[0]->present()->fullName()]) }} - {{ date('Y-m-d H:i', time()) }}</title>
    @else
        <title>{{ trans('admin/users/general.print_assigned') }} - {{ date('Y-m-d H:i', time()) }}</title>
    @endisset

    <link rel="shortcut icon" type="image/ico" href="{{ ($snipeSettings) && ($snipeSettings->favicon!='') ?  Storage::disk('public')->url(e($snipeSettings->favicon)) : config('app.url').'/favicon.ico' }}">

    <link rel="stylesheet" href="{{ url(mix('css/dist/bootstrap-table.css')) }}">

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
            border-width: 2px;
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

        @media print {
            .page2 {
                page-break-before: always;
            }
        }
    </style>


</head>
<body>

@foreach ($users as $show_user)
    <div class="page1">
        @if ($snipeSettings->logo_print_assets=='1')
            <h1>
                Ausleihbestätigung / Loan confirmation (für ITSD / for ITSD)
            </h1>
            <img class="print-logo" src="{{ config('app.url') }}/uploads/{{ $snipeSettings->logo }}">
        @endif

        <h3>
            Zugewiesen an: / Assigned to:
            {{ $show_user->present()->fullName() }}
            {{ ($show_user->employee_num!='') ? ' (#'.$show_user->employee_num.') ' : '' }}
            {{ ($show_user->jobtitle!='' ? ' - '.$show_user->jobtitle : '') }}
        </h3>
        @if ($show_user->assets->count() > 0)
            @php
            $counter = 1;
        @endphp
            <table class="inventory">
                <thead>
                    <tr>
                        <th style="width: 20px;"></th>
                        <th style="width: 20%;">Inventarnummer /<br>Inventory-ID</th>
                        <th style="width: 20%;">Name</th>
                        <th style="width: 10%;">Ausgabe am /<br>Issued on</th>
                        <th style="width: 10%;">Rückgabe bis /<br>Return until</th>
                        <th style="width: 40%;">Notizen / Notes</th>
                    </tr>
                </thead>

                @foreach ($show_user->assets as $asset)
                @php
                $checkout_date_object = date_create_from_format("Y-m-d H:i:s", $asset->last_checkout); 
                $checkin_date_object = date_create_from_format("Y-m-d H:i:s", $asset->expected_checkin);
            @endphp
                    <tr>
                        <td>{{ $counter }}</td>
                        <td>{{ $asset->asset_tag }}</td>
                        <td>{{ $asset->name }}</td>
                        <td>{{ Helper::getFormattedDateObject( $asset->last_checkout, "date", false ) }}</td>
                        <td>{{ Helper::getFormattedDateObject( $asset->expected_checkin, "date", false )  }}</td>
                        <td>{{ $asset->notes }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @if ($show_user->accessories->count() > 0)
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

                @foreach ($show_user->accessories as $accessory)
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

            <h3>The return deadlines are listed next to the device. The device must be returned by the specified return deadline!</h3>
            <h3>The borrower is personally liable for the borrowed item. With your signature you confirm that you have accepted the hardware listed above.</h3>
            <br>
            <br>
            <br>
            <p>Erstellt am / Created at: {{ Helper::getFormattedDateObject(now(), 'datetime', false) }}</p>
            <br>
            <br>
            <table class="signature">
                <tr>
                    <td>Unterschrift / Signature:</td>
                    <td>__________________________________________</td>
                <br>
                    <td>Abholdatum / Pick-up date:</td>
                    <td>__________________________________________</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="page2">
    @if ($snipeSettings->logo_print_assets=='1')
            <h1>
                Ausleihbestätigung / Loan confirmation (für Entlieher / for Borrower)
            </h1>
            <img class="print-logo" src="{{ config('app.url') }}/uploads/{{ $snipeSettings->logo }}">
        @endif

        <h3>
            Zugewiesen an: / Assigned to:
            {{ $show_user->present()->fullName() }}
            {{ ($show_user->employee_num!='') ? ' (#'.$show_user->employee_num.') ' : '' }}
            {{ ($show_user->jobtitle!='' ? ' - '.$show_user->jobtitle : '') }}
        </h3>
        @if ($show_user->assets->count() > 0)
            @php
            $counter = 1;
        @endphp
            <table class="inventory">
                <thead>
                    <tr>
                        <th style="width: 20px;"></th>
                        <th style="width: 20%;">Inventarnummer /<br>Inventory-ID</th>
                        <th style="width: 20%;">Name</th>
                        <th style="width: 10%;">Ausgabe am /<br>Issued on</th>
                        <th style="width: 10%;">Rückgabe bis /<br>Return until</th>
                        <th style="width: 40%;">Notizen / Notes</th>
                    </tr>
                </thead>

                @foreach ($show_user->assets as $asset)
                @php
                $checkout_date_object = date_create_from_format("Y-m-d H:i:s", $asset->last_checkout); 
                $checkin_date_object = date_create_from_format("Y-m-d H:i:s", $asset->expected_checkin);
            @endphp
                    <tr>
                        <td>{{ $counter }}</td>
                        <td>{{ $asset->asset_tag }}</td>
                        <td>{{ $asset->name }}</td>
                        <td>{{ Helper::getFormattedDateObject( $asset->last_checkout, "date", false ) }}</td>
                        <td>{{ Helper::getFormattedDateObject( $asset->expected_checkin, "date", false )  }}</td>
                        <td>{{ $asset->notes }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @if ($show_user->accessories->count() > 0)
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

                @foreach ($show_user->accessories as $accessory)
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
            <h3>The return deadlines are listed next to the device. The device must be returned by the specified return deadline!</h3>
            <h3>The borrower is personally liable for the borrowed item. With your signature you confirm that you have accepted the hardware listed above.</h3>
            <br>
            <br>
            <br>
            <p>Erstellt am / Created at: {{ Helper::getFormattedDateObject(now(), 'datetime', false) }}</p>
        </div>
    </div>
@endforeach
</body>
</html>
