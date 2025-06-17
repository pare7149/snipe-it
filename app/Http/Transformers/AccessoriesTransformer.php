<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\Accessory;
use App\Models\AccessoryCheckout;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

class AccessoriesTransformer
{
    public function transformAccessories(Collection $accessories, $total)
    {
        $array = [];
        foreach ($accessories as $accessory) {
            $array[] = self::transformAccessory($accessory);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    public function transformAccessory(Accessory $accessory)
    {
        $array = [
            'id' => $accessory->id,
            'name' => e($accessory->name),
            'image' => ($accessory->image) ? Storage::disk('public')->url('accessories/'.e($accessory->image)) : null,
            'company' => ($accessory->company) ? ['id' => $accessory->company->id, 'name'=> e($accessory->company->name)] : null,
            'manufacturer' => ($accessory->manufacturer) ? ['id' => $accessory->manufacturer->id, 'name'=> e($accessory->manufacturer->name)] : null,
            'supplier' => ($accessory->supplier) ? ['id' => $accessory->supplier->id, 'name'=> e($accessory->supplier->name)] : null,
            'model_number' => ($accessory->model_number) ? e($accessory->model_number) : null,
            'category' => ($accessory->category) ? ['id' => $accessory->category->id, 'name'=> e($accessory->category->name)] : null,
            'location' => ($accessory->location) ? ['id' => $accessory->location->id, 'name'=> e($accessory->location->name)] : null,
            'notes' => ($accessory->notes) ? Helper::parseEscapedMarkedownInline($accessory->notes) : null,
            'qty' => ($accessory->qty) ? (int) $accessory->qty : null,
            'purchase_date' => ($accessory->purchase_date) ? Helper::getFormattedDateObject($accessory->purchase_date, 'date') : null,
            'purchase_cost' => Helper::formatCurrencyOutput($accessory->purchase_cost),
            'order_number' => ($accessory->order_number) ? e($accessory->order_number) : null,
            'min_qty' => ($accessory->min_amt) ? (int) $accessory->min_amt : null, // Legacy - should phase out - replaced by below, for the bootstrap table formatter
            'min_amt' => ($accessory->min_amt) ? (int) $accessory->min_amt : null,
            'remaining_qty' => (int) ($accessory->qty - $accessory->checkouts_count), // Legacy - should phase out - replaced by below, for the bootstrap table formatter
            'remaining' => (int) ($accessory->qty - $accessory->checkouts_count),
            'checkouts_count' =>  $accessory->checkouts_count,
            'created_by' => ($accessory->adminuser) ? [
                'id' => (int) $accessory->adminuser->id,
                'name'=> e($accessory->adminuser->present()->fullName()),
            ] : null,
            'created_at' => Helper::getFormattedDateObject($accessory->created_at, 'datetime'),
            'updated_at' => Helper::getFormattedDateObject($accessory->updated_at, 'datetime'),
        ];

        $permissions_array['available_actions'] = [
            'checkout' => Gate::allows('checkout', Accessory::class),
            'checkin' =>  false,
            'update' => Gate::allows('update', Accessory::class),
            'delete' => $accessory->checkouts_count === 0 && Gate::allows('delete', Accessory::class),
            'clone' => Gate::allows('create', Accessory::class),
            
        ];

        $permissions_array['user_can_checkout'] = false;

        if (($accessory->qty - $accessory->checkouts_count) > 0) {
            $permissions_array['user_can_checkout'] = true;
        }

        $array += $permissions_array;

        return $array;
    }

    public function transformAssignedAccessories(Collection $accessories, $total)
    {
        $array = [];
        foreach ($accessories as $accessory) {
            $array[] = self::transformAssignedAccessory($accessory, $total);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    public function transformAssignedAccessory($accessory, $total)
    {
        $actions = "";
        if (Gate::allows('checkin', Accessory::class))
            $actions = '<a target="_blank" href="' . config('app.url') . '/accessories/'. $accessory->pivot->id . '/checkin" class="btn btn-sm bg-purple" data-tooltip="true" title="' . trans('general.checkin_tooltip') . '">' . trans('general.checkin') . '</a><a target="_blank" href="' . config('app.url') . '/accessories/' . $accessory->pivot->id . '/update" class="btn btn-sm bg-orange ml-4" data-tooltip="true" title="' . trans('general.checkin_tooltip') . '">' . trans('general.update') . '</a>';
    

        $array = [
            'id' => $accessory->pivot->id,
            'name' => $accessory->present()->nameUrl,
            'note' => $accessory->pivot->note,
            'created_at' => Helper::getFormattedDateObject(AccessoryCheckout::find($accessory->pivot->id)->created_at, 'datetime'),
            'expected_checkin' => Helper::getFormattedDateObject(AccessoryCheckout::find($accessory->pivot->id)->expected_checkin, "date"),
            'available_actions' => $actions,
        ];

        return $array;
    }

    public function transformCheckedoutAccessory($accessory_checkouts, $total)
    {
        $array = [];

        foreach ($accessory_checkouts as $checkout) {
            $array[] = [
                'id' => $checkout->id,
                'assigned_to' => $this->transformAssignedTo($checkout),
                'note' => $checkout->note ? e($checkout->note) : null,
                'created_by' => $checkout->adminuser ? [
                    'id' => (int) $checkout->adminuser->id,
                    'name'=> e($checkout->adminuser->present()->fullName),
                ]: null,
                'created_at' => Helper::getFormattedDateObject($checkout->created_at, 'datetime'),
                'expected_checkin' => Helper::getFormattedDateObject($checkout->expected_checkin, "date"),
                'available_actions' => Gate::allows('checkout', Accessory::class) ? ['checkin' => true, 'update' => true] : ['checkin' => false],
            ];
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    public function transformAssignedTo($accessoryCheckout)
    {
        if (is_null($accessoryCheckout->assigned)) {
            return null;
        }

        if ($accessoryCheckout->checkedOutToUser()) {
            return (new UsersTransformer)->transformUserCompact($accessoryCheckout->assigned);
        } elseif ($accessoryCheckout->checkedOutToLocation()) {
            return (new LocationsTransformer())->transformLocationCompact($accessoryCheckout->assigned);
        } elseif ($accessoryCheckout->checkedOutToAsset()) {
            return (new AssetsTransformer())->transformAssetCompact($accessoryCheckout->assigned);
        }
    }
}
