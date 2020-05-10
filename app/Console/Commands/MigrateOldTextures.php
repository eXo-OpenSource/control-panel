<?php

namespace App\Console\Commands;

use App\Models\Texture;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateOldTextures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exo:texture:migrate';

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
        $textures = Texture::where('Image', 'NOT LIKE', '%/storage/textures/%')->get();
        $client = new \GuzzleHttp\Client();
        // https://picupload.pewx.de/textures/exo_trust-1.png


        foreach($textures as $texture) {
            $this->info("Downloading texture {$texture->Name}");
            $fileName = Str::random(40) . '.' . pathinfo($texture->Image, PATHINFO_EXTENSION);

            while(true) {
                if(!Storage::disk('textures')->exists($fileName)) {
                    break;
                }
                $fileName = Str::random(40) . '.' . pathinfo($texture->Image, PATHINFO_EXTENSION);
            }

            try {
                $response = $client->request('GET', 'https://picupload.pewx.de/textures/' . $texture->Image, [
                    'sink' => storage_path('app/public/textures/' . $fileName)
                ]);

                if($response->getStatusCode() === 200) {
                    $this->info("Downloaded texture {$texture->Name} successfully");
                    $texture->OldImage = $texture->Image;
                    $texture->Image = env('APP_URL') .  '/storage/textures/' . $fileName;
                    $texture->save();
                } else {
                    $this->error("Downloaded texture {$texture->Name} failed");
                }
            } catch(ClientException $e) {
                $this->error("Downloaded texture {$texture->Name} failed");
            }
        }

        $this->info('Migration completed');

        /*
        $path = Storage::disk('textures')->put(
            '', $request->file('texture')
        );

        $texture = new Texture();
        $texture->UserId = auth()->user()->Id;
        $texture->Name = $data['name'];
        $texture->Image = env('APP_URL') .  '/storage/textures/' . $path;
        $texture->Model = $data['vehicle'];
        $texture->Status = 0;
        $texture->Public = $data['type'];
        $texture->Admin = 0;
        $texture->Date = new \DateTime();
        $texture->Earnings = 0;
        $texture->save();
        */
    }
}
