<?php

namespace  Tassili\Tassili\Commands;

use Illuminate\Console\Command;
use App\Models\User; // Ensure you import the User model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Tassili\Tassili\Utils\TransformString;
use Tassili\Tassili\Utils\CrudPart;

class CrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Crud';

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
        $panel = $this->choice('Choose a panel ?', $panelList, 0);

        $this->info("You Choose this panel : $panel");




// Récupérer le tableau des modèles depuis la config
        $modelList = config('tassili.modelList', []);

        // Vérifier s’il y a des modèles
        if (empty($modelList)) {
            $this->error("No model in config('tassili.modelList').");
            return 1;
        }

        // Demander à l’utilisateur de choisir
        $choix = $this->choice('Choose a model ?', $modelList, 0);

        $this->info("You Choose this model : $choix");

       
        $transform = new TransformString();
        $crudPart = new CrudPart();

        $modelLink = $transform->transformLink($choix);
        $modelUrl = $transform->transformUrl($choix);
        $panelCamel = ucfirst($panel);

        $piece1 = $crudPart->getPiece1($choix, $modelLink, $modelUrl,$panel,$panelCamel);
        $piece2 = $crudPart->getPiece2($choix, $modelLink, $modelUrl,$panel,$panelCamel);
        $piece3 = $crudPart->getPiece3($choix, $modelLink, $modelUrl,$panel,$panelCamel);
        $piece4 = $crudPart->getPiece4($choix, $modelLink, $modelUrl,$panel,$panelCamel);

        $bigDossier = base_path("app/Http/Controllers/Tassili/{$panelCamel}");

        if (!File::exists($bigDossier)) {
            $this->error('❌ This panel dont exist.');
            return;
        }


        $dossier = base_path("app/Http/Controllers/Tassili/{$panelCamel}/Crud/{$choix}");
        $custom = "{$dossier}/Customs";

        if (File::exists($dossier)) {
            $this->error('❌ CRUD already exists.');
            return;
        }

        File::makeDirectory($dossier, 0755, true);
        File::makeDirectory($custom, 0755, true);

        File::put("{$dossier}/CreatorController.php", $piece1);
        File::put("{$dossier}/UpdatorController.php", $piece2);
        File::put("{$dossier}/ListingController.php", $piece3);
        File::put("{$custom}/Custom1Controller.php", $piece4);

        $crud = new \Tassili\Tassili\Models\TassiliCrud() ;
        $crud->panel = $panel;
        $crud->model = $choix;
        $crud->label = $modelLink;
        $crud->route = '/'.$panel.'/' . $modelUrl;
        $crud->icon = 'description';
        $crud->active = true;
        $crud->save();


        $vueTarget = base_path("resources/js/Pages/TassiliPages/{$panelCamel}/Crud/{$choix}");
        File::copyDirectory(base_path('vendor/tassili/tassili/Fichiers/CrudFiles'), $vueTarget);

        foreach (File::allFiles($vueTarget) as $file) {
            if ($file->getExtension() === 'txt') {
                File::move($file->getPathname(), $file->getPath() . '/' . str_replace('.txt', '.vue', $file->getFilename()));
            }
        }


        $this->info("✅ CRUD {$choix} created with success !");
       


    }
}