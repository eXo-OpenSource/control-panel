<?php

namespace App\Console\Commands;

use App\Models\Texture;
use App\Models\Vehicle;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateVehicleTextures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exo:texture:migrate-vehicles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $textures = Texture::all();
        $vehicles = Vehicle::all();

        foreach($vehicles as $vehicle) {
            $tunings = $vehicle->Tunings;

            $tuningsData = json_decode($tunings);

            if(!isset($tuningsData[0]->Texture))
            {
                continue;
            }

            $vehicleTextures = $tuningsData[0]->Texture;

            if(gettype($vehicleTextures) === 'array' && count($vehicleTextures) === 0)
                continue;


            $updated = false;
            foreach($vehicleTextures as $key => $value)
            {
                if (str_starts_with($value, 'https://picupload.pewx.de')) {
                    $textureName = str_replace('https://picupload.pewx.de/textures/', '', $value);

                    $texture = $textures->firstWhere('OldImage', $textureName);

                    if ($texture !== null) {
                        $this->info('Image has been updated');
                        $value = $texture->Image;
                        $updated = true;
                    } else {
                        $this->error('Image has been deleted');
                        $value = '';
                        $updated = true;
                    }
                } else if (str_starts_with($value, 'https://cp.exo-reallife.de')) {
                    $texture = $textures->firstWhere('Image', $value);

                    if ($texture === null) {
                        $this->error('Image has been deleted');
                        $value = '';
                        $updated = true;
                    }
                }

                $vehicleTextures->{$key} = $value;
            }

            if ($updated)
            {
                $vehicle->Tunings = json_encode($tuningsData);
                $vehicle->save();
                $this->info('Updated vehicle ' . $vehicle->Id);
            }

            /*
            $this->info("Downloading texture {$texture->OldImage}");
            $fileName = str_replace('https://cp.exo-reallife.de/storage/textures/', '', $texture->Image);
            $fileName = str_replace('http://localhost:8000/storage/textures/', '', $fileName);


            if(Storage::disk('textures')->exists($fileName)) {
                $this->error("Texture {$texture->Name} already exists?");
                continue;
            }

            try {
                $response = $client->request('GET', 'https://files.deangur.com/textures/' . $texture->OldImage, [
                    'sink' => storage_path('app/public/textures/' . $fileName)
                ]);

                if($response->getStatusCode() === 200) {
                    $this->info("Downloaded texture {$texture->Name} successfully");
                    $texture->Image = env('APP_URL') .  '/storage/textures/' . $fileName;
                    $texture->save();
                } else {
                    $this->error("Downloaded texture {$texture->Name} failed");
                }
            } catch(ClientException $e) {
                $this->error("Downloaded texture {$texture->Name} failed");
            }
            */
        }

        $this->info('Migration completed');
    }
}
