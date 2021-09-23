<?php

namespace App\Jobs;

use App\Models\IpHub;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/*
DROP TABLE IF EXISTS `vrp_iphub`;
CREATE TABLE `vrp_iphub`  (
  `Id` int(0) NOT NULL AUTO_INCREMENT,
  `Ip` varchar(45) NOT NULL,
  `Hostname` varchar(255) NOT NULL,
  `CountryCode` varchar(2) NOT NULL,
  `CountryName` varchar(255) NOT NULL,
  `ASN` int(0) NOT NULL,
  `ISP` varchar(255) NOT NULL,
  `Block` tinyint(0) NOT NULL,
  `CreatedAt` datetime(0) NOT NULL,
  `UpdatedAt` datetime(0) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `Ip`(`Ip`) USING BTREE
);
*/
class IpHubLookup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Cache::has('iphub:skip'))
        {
            return;
        }

        $result = DB::select('SELECT DISTINCT l.Ip FROM vrp_public_logs.vrpLogs_Login AS l WHERE Ip NOT IN (SELECT Ip FROM vrp_public.vrp_iphub) ORDER BY l.Id DESC LIMIT ?;', [env('IPHUB_PER_ACTION')]);

        if (!isset($result) || count($result) === 0)
        {
            return;
        }

        $ips = collect($result)->pluck('Ip');

        foreach($ips as $ip)
        {
            $ipHub = IpHub::where('Ip', $ip)->first();

            if ($ipHub == null)
            {
                $ipHub = new IpHub();
            }

            $hostname = gethostbyaddr($ip);
            $ipHub->Hostname =  $hostname !== false ? $hostname : '';

            $response = Http::withHeaders(['X-Key' => env('IPHUB_KEY')])->get('https://v2.api.iphub.info/ip/' . $ip);

            if ($response->status() === 200) // OK
            {
                $data = $response->json();
                $ipHub->Ip = $data['ip'];
                $ipHub->CountryCode = $data['countryCode'];
                $ipHub->CountryName = $data['countryName'];
                $ipHub->ASN = $data['asn'];
                $ipHub->ISP = $data['isp'];
                $ipHub->Block = $data['block'];
                $ipHub->save();
            }
            elseif ($response->status() === 429) // Too Many Requests
            {
                Log::error($response->status());
                Log::error($response->body());
                Cache::put('iphub:skip', true, Carbon::now()->addHours(1));
                return;
            }
            elseif ($response->status() === 403) // Forbidden
            {
                Log::error($response->status());
                Log::error($response->body());
                Cache::put('iphub:skip', true, Carbon::now()->addHours(1));
                return;
            }
            else // Other?
            {
                Log::error($response->status());
                Log::error($response->body());
                Cache::put('iphub:skip', true, Carbon::now()->addHours(1));
                return;
            }
        }
    }
}
