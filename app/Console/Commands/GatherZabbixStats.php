<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Asset;
use App\Models\Actionlog;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckoutCheckinReturnValue

{
    public int $checkouts;
    public int $checkins;
}

class GatherZabbixStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pascal:gather-zabbix-stats {company_id} {output_file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $checked_out_status = 4;
    protected $overdue_status = 5;
    protected $usable_status = 11;

    private function get_checkouts_checkins_today($company_id): CheckoutCheckinReturnValue
    {
        $checkouts = Actionlog::whereDate('action_logs.created_at', Carbon::today())
            ->leftJoin("assets", function($join) 
                {
                    $join->on("action_logs.item_id", "=", "assets.id");
                })
            ->where("action_logs.item_type", Asset::class)
            ->where("action_logs.target_type", User::class)
            ->where("assets.company_id", $company_id)
            ->where("action_logs.action_type", "checkout")
            ->get();

        $checkins = Actionlog::whereDate('action_logs.created_at', Carbon::today())
            ->leftJoin("assets", function($join) 
                {
                    $join->on("action_logs.item_id", "=", "assets.id");
                })
            ->where("action_logs.item_type", Asset::class)
            ->where("action_logs.target_type", User::class)
            ->where("assets.company_id", $company_id)
            ->where("action_logs.action_type", "checkin from")
            ->get();

        $data = new CheckoutCheckinReturnValue();
        $data->checkouts = count($checkouts);
        $data->checkins = count($checkins);

        return $data;
    }

    private function get_average_checkout_time_today(int $company_id): float
    {
        $checkouts = Actionlog::whereDate('action_logs.created_at', Carbon::today())
            ->leftJoin("assets", function($join) 
                {
                    $join->on("action_logs.item_id", "=", "assets.id");
                })
            ->where("action_logs.item_type", Asset::class)
            ->where("action_logs.target_type", User::class)
            ->where("assets.company_id", $company_id)
            ->where("action_logs.action_type", "checkout")
            ->whereNotNull("action_logs.log_meta")
            ->get();

        $checkout_periods = [];

        foreach ($checkouts as $checkout)
        {
            $meta = json_decode($checkout["log_meta"], true);
            if ($meta["expected_checkin"])
            {
                if ($meta["expected_checkin"]["new"] != null)
                {
                    $checkin_date = new Carbon($meta["expected_checkin"]["new"]);
                    $checkout_date = new Carbon($checkout->created_at);
                    $checkout_period = $checkin_date->timestamp - $checkout_date->timestamp;
                    $day_period = round ($checkout_period / 86400, 1);
                    
                    if ($day_period == 0)
                        $day_period = 1;

                    array_push($checkout_periods, $day_period);
                }
            }
        }

        if (count($checkout_periods) == 0)
            return 0;

        return array_sum($checkout_periods) / count($checkout_periods);
    }

     private function get_average_checkout_time(int $company_id): float
    {
        $checkouts = Actionlog::where("assets.company_id", $company_id)
            ->leftJoin("assets", function($join) 
                {
                    $join->on("action_logs.item_id", "=", "assets.id");
                })
            ->where("action_logs.item_type", Asset::class)
            ->where("action_logs.target_type", User::class)
            ->where("action_logs.action_type", "checkout")
            ->whereNotNull("action_logs.log_meta")
            ->get();

        $checkout_periods = [];

        foreach ($checkouts as $checkout)
        {
            $meta = json_decode($checkout["log_meta"], true);
            if (isset($meta["expected_checkin"]))
            {
                if ($meta["expected_checkin"]["new"] != null)
                {
                    $checkin_date = new Carbon($meta["expected_checkin"]["new"]);
                    $checkout_date = new Carbon($checkout->created_at);
                    $checkout_period = $checkin_date->timestamp - $checkout_date->timestamp;
                    $day_period = round ($checkout_period / 86400, 1);
                    
                    if ($day_period == 0)
                        $day_period = 1;

                    array_push($checkout_periods, $day_period);
                }
            }
        }

        if (count($checkout_periods) == 0)
            return 0;

        $this->info(json_encode($checkout_periods));

        return array_sum($checkout_periods) / count($checkout_periods);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $company_id = $this->argument("company_id");
        $output_file = $this->argument("output_file");

        $assets = Asset::where("company_id", $company_id)->get();

        $available = 0;
        $checked_out = 0;
        $overdrawn = 0;
        $unavailable = 0;

        $users_with_checked_out = [];
        $users_with_overdrawn = [];

        foreach ($assets as $asset)
        {
            switch ($asset->status_id)
            {
                case ($this->usable_status):
                    $available++;
                    break;
                case ($this->checked_out_status):
                    $checked_out++;
                    array_push($users_with_checked_out, $asset->assigned_to);
                    break;
                case ($this->overdue_status):
                    $overdrawn++;
                    array_push($users_with_overdrawn, $asset->assigned_to);
                    break;
                default:
                    $unavailable++;
                    break;
            }
        }

        $users_with_checked_out = array_unique($users_with_checked_out, SORT_NUMERIC);
        $users_with_overdrawn = array_unique($users_with_overdrawn, SORT_NUMERIC);

        $users_logged_in = count(DB::table(config('session.table'))
            ->distinct()
            ->select(["users.id"])
            ->whereNotNull("user_id")
            ->leftJoin("users", config('session.table') . '.user_id', '=', 'users.id')
            ->get());

        $checkins_checkouts_today = $this->get_checkouts_checkins_today($company_id);
        $average_checkout_time_today = $this->get_average_checkout_time_today($company_id);
        $average_checkout_time = $this->get_average_checkout_time($company_id);

        $out_fd = fopen($output_file, "w");
        fwrite($out_fd, "- kpi.assets.available " . $available . "\n");
        fwrite($out_fd, "- kpi.assets.unavailable " . $unavailable . "\n");
        fwrite($out_fd, "- kpi.assets.checked_out " . $checked_out . "\n");
        fwrite($out_fd, "- kpi.assets.overdrawn " . $overdrawn. "\n");
        fwrite($out_fd, "- kpi.users.with_checked_out " . count($users_with_checked_out) . "\n");
        fwrite($out_fd, "- kpi.users.with_overdrawn " . count($users_with_overdrawn) . "\n");
        fwrite($out_fd, "- kpi.users.logged_in " . $users_logged_in . "\n");
        fwrite($out_fd, "- kpi.assets.checkedout_today " . $checkins_checkouts_today->checkouts . "\n");
        fwrite($out_fd, "- kpi.assets.checkedin_today " . $checkins_checkouts_today->checkins . "\n");
        fwrite($out_fd, "- kpi.assets.average_checkout_time_today ". $average_checkout_time_today . "\n");
        fwrite($out_fd, "- kpi.assets.average_checkout_time ". $average_checkout_time . "\n");
        fclose($out_fd);
    }
}
