<?php

namespace  Tassili\Tassili\Commands;

use Illuminate\Console\Command;
use App\Models\User; // Ensure you import the User model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class TassiliCreator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tassili:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the package';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

       

$licenseKey = env('GUMROAD_TASSILI_LICENSE_KEY');

// Vérifie si la clé de licence est définie
if (empty($licenseKey)) {
    $this->error('❌ GUMROAD_LICENSE_KEY is not set in your environment file.');
    return Command::FAILURE;
}

$productId = 'iG5U7fLhrV5MXpZfeSQmxQ=='; // Remplacez par l'ID de votre produit Gumroad

// Vérification de la licence via l'API de Gumroad
$response = Http::asForm()->post('https://api.gumroad.com/v2/licenses/verify', [
    'product_id' => $productId,
    'license_key' => $licenseKey,
    'increment_uses_count' => true,
]);

$data = $response->json();

if (!isset($data['success']) || !$data['success']) {
    $this->error('❌ Invalid Key.');
    return Command::FAILURE;
}

$uses = $data['uses'] ?? 0;
$maxUses = $data['purchase']['max_uses'] ?? 2;

if ($uses > $maxUses) {
    $this->error('❌ Licence already used');
    return Command::FAILURE;
}




//////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////



       $path = resource_path('js/Vendor');

        if (!File::exists($path)) {
             File::makeDirectory($path, 0755, true);
         }



         $sourcePath1 = base_path('vendor/tassili/tassili/Fichiers/RouteFiles/tassili.php');

         $destinationPath1 = base_path('routes/tassili.php');

          if (File::exists($sourcePath1)) {
                File::copy($sourcePath1, $destinationPath1); // Crée destination.txt s'il n'existe pas
            }


        $filePath = base_path('routes/web.php');
            $content = file_get_contents($filePath);

                if (!str_contains($content, "require __DIR__.'/tassili.php';")) {
                     $linesToAdd = <<<PHP

                      require __DIR__.'/tassili.php';

                      PHP;

             file_put_contents($filePath, $linesToAdd, FILE_APPEND);
             }


         ///////////////////////////////////////////////////////////////////////////////////////////////////////
         //////////////////////////////////////////////////////////////////////////////////////////////////////
            $sourcePath4 = base_path('vendor/tassili/tassili/Fichiers/TassiliLibs1');
       
            $temp4 = 'resources/js/Vendor/TassiliLibs'  ;
    
            $directory4 = base_path($temp4);
    
            File::copyDirectory($sourcePath4, $directory4);
    
            if (!File::exists($directory4)) {
                return response()->json(['error' => 'Dossier non trouvé.'], 404);
            }
        
            // Récupère tous les fichiers (même dans les sous-dossiers)
            $files4 = File::allFiles($directory4);
        
            foreach ($files4 as $file4) {
                if ($file4->getExtension() === 'txt') {
                    // Nouveau nom avec extension .vue
                    $newFileName4 = str_replace('.txt', '.vue', $file4->getFilename());
        
                    // Nouveau chemin complet
                    $newFilePath4 = $file4->getPath() . '/' . $newFileName4;
        
                    // Renommer le fichier
                    File::move($file4->getPathname(), $newFilePath4);
                }
            }


        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////


         $sourcePath5 = base_path('vendor/tassili/tassili/Fichiers/TassiliDev1');
       
            $temp5 = 'resources/js/Pages/TassiliDev'  ;
    
            $directory5 = base_path($temp5);
    
            File::copyDirectory($sourcePath5, $directory5);
    
            if (!File::exists($directory5)) {
                return response()->json(['error' => 'Dossier non trouvé.'], 404);
            }
        
            // Récupère tous les fichiers (même dans les sous-dossiers)
            $files5 = File::allFiles($directory5);
        
            foreach ($files5 as $file5) {
                if ($file5->getExtension() === 'txt') {
                    // Nouveau nom avec extension .vue
                    $newFileName5 = str_replace('.txt', '.vue', $file5->getFilename());
        
                    // Nouveau chemin complet
                    $newFilePath5 = $file5->getPath() . '/' . $newFileName5;
        
                    // Renommer le fichier
                    File::move($file5->getPathname(), $newFilePath5);
                }
            }





    }
}