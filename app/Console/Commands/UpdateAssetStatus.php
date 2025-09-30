<?php

namespace App\Console\Commands;

use App\Models\Asset;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class UpdateAssetStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pascal:update-asset-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $checked_out = 4;
    protected $overdue = 5;
    protected $usable = 11;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $assets = Asset::all();

        foreach ($assets as $asset) 
        { 
            if ($asset->expected_checkin) 
            {    
                // Set checked_out status if user forgot during checkout
                if ($asset->status_id != $this->checked_out && $asset->status_id != $this->overdue)
                {
                    $asset->status_id = $this->checked_out;
                    $asset->save();
                }

                # Check if Asset is overdue
                if (Carbon::now()->gt($asset->expected_checkin->addDay()))
                {
                    # Check if Asset does not already have overdrawn status
                    if ($asset->status_id == $this->checked_out && $asset->status_id != $this->overdue)
                    {
                        $asset->status_id = $this->overdue;
                        $asset->save();
                    }
                }
                # If asset has been extended set status back to cheked_out
                elseif ($asset->status_id == $this->overdue)
                {
                    $asset->status_id = $this->checked_out;
                    $asset->save();
                }
            }
            # Set usable_status if operator forgot during checkin
            elseif ($asset->status_id == $this->checked_out || $asset->status_id == $this->overdue) 
            {
                if ($asset->assigned_to == null) 
                {
                    $asset->status_id = $this->usable;
                    $asset->save();
                }
            }
        }
    }
}
