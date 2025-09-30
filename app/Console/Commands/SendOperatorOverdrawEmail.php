<?php

namespace App\Console\Commands;

use App\Models\Asset;
use App\Models\Company;
use Illuminate\Console\Command;
use App\Mail\OperatorOverdrawEmail;
use Illuminate\Support\Facades\Mail;

class SendOperatorOverdrawEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pascal:send-operator-overdraw-email {company_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $overdue = 5;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $company = Company::find($this->argument("company_id"));

        $assets = Asset::where("status_id", $this->overdue)->where("company_id", $this->argument("company_id"))->get();
        Mail::to($company->email)->queue(new OperatorOverdrawEmail($assets));
    }
}
