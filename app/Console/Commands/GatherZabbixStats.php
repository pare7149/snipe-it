<?php

namespace App\Console\Commands;

use App\Models\Asset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        $users_logged_in = DB::table(config('session.table'))->count();

        $out_fd = fopen($output_file, "w");
        fwrite($out_fd, "- kpi.assets.available " . $available . "\n");
        fwrite($out_fd, "- kpi.assets.unavailable " . $unavailable . "\n");
        fwrite($out_fd, "- kpi.assets.checked_out " . $checked_out . "\n");
        fwrite($out_fd, "- kpi.assets.overdrawn " . $overdrawn. "\n");
        fwrite($out_fd, "- kpi.users.with_checked_out " . count($users_with_checked_out) . "\n");
        fwrite($out_fd, "- kpi.users.with_overdrawn " . count($users_with_overdrawn) . "\n");
        fwrite($out_fd, "- kpi.users.logged_in " . $users_logged_in . "\n");
        fclose($out_fd);
    }
}
