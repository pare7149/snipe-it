<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Asset;
use App\Models\Company;
use App\Models\Setting;
use Illuminate\Console\Command;
use App\Models\Recipients\AlertRecipient;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ExpectedCheckinNotification;
use App\Notifications\ExpectedCheckinAdminNotification;

class SendExpectedCheckinAlerts extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'snipeit:expected-checkin {company_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue or upcoming expected checkins.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $settings = Setting::getSettings();
        $interval = $settings->audit_warning_days ?? 0;
        $today = Carbon::now();
        $interval_date = $today->copy()->addDays($interval);
        $company = Company::find($this->argument("company_id"));

        $assets = Asset::whereNull('deleted_at')
        ->where("company_id", $company->id)
        ->DueOrOverdueForCheckin($settings)->orderBy('assets.expected_checkin', 'desc')->get();

        $this->info($assets->count().' assets must be checked in on or before '.$interval_date.' is deadline');


        foreach ($assets as $asset) {
            if ($asset->assignedTo && (isset($asset->assignedTo->email)) && ($asset->assignedTo->email!='') && $asset->checkedOutToUser()) {
                $this->info('Sending User ExpectedCheckinNotification to: '.$asset->assignedTo->email);
                $asset->assignedTo->notify((new ExpectedCheckinNotification($asset)));
            }
        }

        if (($assets) && ($assets->count() > 0) && ($company->email != '')) {
            // Send a rollup to the admin, if settings dictate
            $recipients = collect(explode(',', $company->email))->map(function ($item) {
                return new AlertRecipient($item);
            });

            $this->info('Sending Admin ExpectedCheckinNotification to: '.$company->email);
            Notification::send($recipients, new ExpectedCheckinAdminNotification($assets));

	    }
    }
}
