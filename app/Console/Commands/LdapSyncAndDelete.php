<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class LdapSyncAndDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pascal:ldap-sync-and-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::where("activated", true)->where("ldap_import", true)->update(["activated" => false]);

        Artisan::call("snipeit:ldap-sync");

        $inactive_users = User::where("activated", false)->where("ldap_import", true)->get();
        foreach ($inactive_users as $user)
        {
            if (($user->assets->count() === 0)
                && ($user->licenses->count() === 0)
                && ($user->consumables->count() === 0)
                && ($user->accessories->count() === 0)
                && ($user->managedLocations->count() === 0)
                && ($user->managesUsers->count() === 0)
                && ($user->deleted_at == '')
            )
            {
                $this->info("Deleting user: " . $user->username);
                $user->delete();
                continue;
            }
            $this->info("Skipping user: " . $user->username);
        }

        Artisan::call("snipeit:merge-users");
    }
}
