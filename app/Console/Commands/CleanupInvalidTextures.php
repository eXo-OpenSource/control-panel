<?php

namespace App\Console\Commands;

use App\Models\Texture;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CleanupInvalidTextures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exo:texture:cleanup';

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

        foreach($textures as $texture) {
            $this->info("Checking texture {$texture->Name}");
            $fileName = str_replace('https://cp.exo-reallife.de/storage/textures/', '', $texture->Image);
            $fileName = str_replace('http://localhost:8000/storage/textures/', '', $fileName);


            if(Storage::disk('textures')->exists($fileName)) {
                $this->info("Texture {$texture->Name} exists");
                continue;
            }

            $this->error("Texture {$texture->Name} has been deleted");
            $texture->delete();
        }

        $this->info('Cleanup completed');
    }
}
