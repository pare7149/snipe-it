<?php

namespace App\Http\Controllers\Accessories;

use App\Models\Accessory;
use Illuminate\Http\Request;
use App\Models\AccessoryCheckout;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class BulkAssignedAccessoriesController extends Controller
{
    public function edit(Request $request) {
        $this->authorize('view', Accessory::class);

        /**
         * No asset IDs were passed
         */
        if (! $request->filled('ids')) {
            return redirect()->back()->with('error', trans('admin/hardware/message.update.no_assets_selected'));
        }

        $checkout_ids = $request->input('ids');
        
        // Figure out later if this is needed
        /*if ($request->input('bulk_actions') === 'checkout') {
            $request->session()->flashInput(['selected_accessory_checkouts' => $checkout_ids]);
            return redirect()->route('hardware.bulkcheckout.show');
        }
        */
        // Figure out where we need to send the user after the update is complete, and store that in the session
        $bulk_back_url = request()->headers->get('referer');
        session(['bulk_back_url' => $bulk_back_url]);

        $checkouts = AccessoryCheckout::whereIn("accessories_checkout.id", $checkout_ids)
            ->rightJoin("accessories", "accessories.id", "=", "accessories_checkout.accessory_id")
            ->select("accessories_checkout.id", "accessories.name")
            ->get();

        if ($checkouts->isEmpty()) {
            Log::debug('No assets were found for the provided IDs', ['ids' => $checkout_ids]);
            return redirect()->back()->with('error', trans('admin/hardware/message.update.assets_do_not_exist_or_are_invalid'));
        }

        switch ($request->input('bulk_actions')) {
            case "update":
                return view("accessories.bulk-update")
                ->with("checkouts", $checkouts);
                break;

            case "checkin":
                $request = new Request();
                $checkin = new AccessoryCheckinController();
                foreach ($checkouts as $checkout)
                {
                    $checkin->store($request, $checkout->id);
                }
                return redirect()->back()->with('success', trans('admin/accessories/message.checkin.success'));
                break;
        }
    }

    public function update(Request $request) : RedirectResponse
    {
        $this->authorize('update', Accessory::class);

        // Get the back url from the session and then destroy the session
        $bulk_back_url = route('hardware.index');

        if ($request->session()->has('bulk_back_url')) {
            $bulk_back_url = $request->session()->pull('bulk_back_url');
        }
     
        if (! $request->filled('ids') || count($request->input('ids')) == 0) {
            return redirect($bulk_back_url)->with('error', trans('admin/hardware/message.update.no_assets_selected'));
        }

        $checkouts = AccessoryCheckout::whereIn('id', $request->input('ids'))->get();

        if ($request->filled("expected_checkin")) {
            
            foreach ($checkouts as $checkout)
            {
                $checkout->expected_checkin = $request->input("expected_checkin");
                $checkout->save();
            }

            return redirect($bulk_back_url)->with('success', trans('admin/hardware/message.update.success'));
        }
        // no values given, nothing to update
        return redirect($bulk_back_url)->with('warning', trans('admin/hardware/message.update.nothing_updated'));
    }
}

