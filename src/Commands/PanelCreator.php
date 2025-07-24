<?php

namespace  Tassili\Tassili\Commands;

use Illuminate\Console\Command;
use App\Models\User; // Ensure you import the User model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Tassili\Tassili\Utils\PanelPart;

class PanelCreator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:panel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Panel';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       $panelList = config('tassili.panelList', []);

        // Vérifier s’il y a des modèles
        if (empty($panelList)) {
            $this->error("No panel in config('tassili.panelList').");
            return 1;
        }

        // Demander à l’utilisateur de choisir
        $choix = $this->choice('Choose a panel ?', $panelList, 0);

        $this->info("You Choose this panel : $choix");

        $middlewareList = config('tassili.middlewareList', []);

        if (empty($middlewareList)) {
            $this->error("No Middleware in config('tassili.middlewareList').");
            return 1;
        }

        $choix2 = $this->choice('Choose a middleware ?',$middlewareList ,0);

        $this->info("You Choose this middleware : $choix2"); 


        $panelCamel = ucfirst($choix);

        $dossier = base_path("app/Http/Controllers/Tassili/{$panelCamel}/Dashboard");
        

        if (File::exists($dossier)) {
            $this->error('❌ Panel already exists.');
            return;
        }


       
        $panelPart = new PanelPart();

        $piece1 = $panelPart->getPiece1($choix, $panelCamel, $choix2);
        $piece2 = $panelPart->getPiece2($choix, $panelCamel , $choix2);
      
        File::makeDirectory($dossier, 0755, true);

        File::put("{$dossier}/DashboardController.php", $piece1);
        File::put("{$dossier}/LoginController.php", $piece2);
        

    


        $vueTarget = base_path("resources/js/Pages/TassiliPages/{$panelCamel}");
        File::copyDirectory(base_path('vendor/tassili/tassili/Fichiers/PanelPage1'), $vueTarget);

        foreach (File::allFiles($vueTarget) as $file) {
            if ($file->getExtension() === 'txt') {
                File::move($file->getPathname(), $file->getPath() . '/' . str_replace('.txt', '.vue', $file->getFilename()));
            }
        }





    }
}