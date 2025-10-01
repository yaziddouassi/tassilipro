<?php

namespace Tassili\Tassili\Utils;
use Illuminate\Support\Str;

class CrudPart
{
   public $piece1;
   public $piece2;
   public $piece3;
   public $piece4;

public function getPiece1($a,$b,$c,$panel,$panelCamel) {


    $this->piece1 = "<?php

namespace App\Http\Controllers\Tassili\\$panelCamel\Crud\\$a;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Tassili\Tassili\Http\Controllers\TassiliCreate;
use Tassili\Tassili\Fields\TextInput;
use App\Http\Controllers\Controller;


class CreatorController extends Controller
{
    private string \$tassiliPanel = '$panel' ;
    private string  \$tassiliModelClass = 'App\Models\\$a';
    private TassiliCreate \$tassili;

     public function __construct()
    {
        \$this->tassili = new TassiliCreate([
            'tassiliShowOther' => true,
            'tassiliDataModelLabel' => '$b',
            'tassiliDataModelTitle' => 'Create $b',
            'tassiliDataRouteListe' => '/$panel/$c',
            'tassiliDataUrlCreate' => '/$panel/$c/create',
            'tassiliModelClass' => \$this->tassiliModelClass,
            'tassiliModelClassName' => '$a',
            'tassiliValidationUrl' => '/$panel/$c/create/validation',
        ]);

        \$this->initField();
    }


     public function initField()
    {
        \$this->tassili->form([
            TextInput::make('name'),
        ]);
    }

    #[Post('$panel/$c/create/validation', middleware: ['tassili.auth'])]
    public function create(Request \$request)
    {
        \$request->validate([
            'name' => ['required'],
        ]);

        \$this->tassili->tassiliRecord = new \$this->tassiliModelClass;
        \$this->tassili->createRecord(\$request);
        \$this->tassili->tassiliRecord->save();
    }

    #[Get('$panel/$c/create', middleware: ['tassili.auth'])]
    public function index(Request \$request)
    {
        return Inertia::render('TassiliPages/$panelCamel/Crud/$a/Creator', [
            'user' => Auth::user(),
            'routes' => \Tassili\Tassili\Models\TassiliCrud::where('active', true)
                      ->where('panel',\$this->tassiliPanel)->get(),
            'tassiliSettings' => \$this->tassili->tassiliSettings,
            'tassiliFields' => \$this->tassili->tassiliFields,
            'tassiliUrlStorage' => config('tassili.storage_url'),
        ]);
    }
    

   

}
    ";

    return $this->piece1;
   }



   public function getPiece2($a,$b,$c,$panel,$panelCamel) {
          $this->piece2 = "<?php

namespace App\Http\Controllers\Tassili\\$panelCamel\Crud\\$a;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Tassili\Tassili\Http\Controllers\TassiliUpdate;
use Tassili\Tassili\Fields\TextInput;
use App\Http\Controllers\Controller;

class UpdatorController extends Controller
{
    private string \$tassiliPanel = '$panel' ;
    private string  \$tassiliModelClass = 'App\Models\\$a';
    private TassiliUpdate \$tassili;

     public function __construct()
    {
        \$this->tassili = new TassiliUpdate([
            'tassiliDataModelLabel' => '$b',
            'tassiliDataModelTitle' => 'Update $b',
            'tassiliDataRouteListe' => '/$panel/$c',
            'tassiliDataUrlCreate' => '/$panel/$c/create',
            'tassiliModelClass' => \$this->tassiliModelClass,
            'tassiliModelClassName' => '$a',
            'tassiliValidationUrl' => '/$panel/$c/updator/validation',
        ]);

        \$this->initField();
    }

     
    public function initField()
    {
        \$this->tassili->form([
            TextInput::make('name'),
        ]);
    }

    #[Post('$panel/$c/updator/validation', middleware: ['tassili.auth'])]
    public function update(Request \$request)
    {
        \$request->validate([
            'name' => ['required'],
        ]);

        \$this->tassili->tassiliRecord = \$this->tassiliModelClass::find(\$request->id);

        if (\$this->tassili->tassiliRecord !== null) {
            \$this->tassili->updateRecord(\$request);
            \$this->tassili->tassiliRecord->save();
        }
    }

    #[Get('$panel/$c/update/{id}', middleware: ['tassili.auth'])]
    public function index(Request \$request)
    {
        \$redirect = \$this->tassili->checkRecord(\$request);

        if (\$redirect) {
            return \$redirect;
        }

        \$this->tassili->initFieldAgain(\$request);

        return Inertia::render('TassiliPages/$panelCamel/Crud/$a/Updator', [
            'user' => Auth::user(),
            'routes' => \Tassili\Tassili\Models\TassiliCrud::where('active', true)
                        ->where('panel',\$this->tassiliPanel)->get(),
            'tassiliSettings' => \$this->tassili->tassiliSettings,
            'tassiliFields' => \$this->tassili->tassiliFields,
            'tassiliRecordInput' => \$this->tassili->tassiliRecordInput,
            'tassiliUrlStorage' => config('tassili.storage_url'),
        ]);
    }

   
    
}

          ";

          return $this->piece2;
   }


   
   public function getPiece3($a,$b,$c,$panel,$panelCamel) {

      $this->piece3 = "<?php

namespace App\Http\Controllers\Tassili\\$panelCamel\Crud\\$a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Tassili\Tassili\Http\Controllers\ListingUtility;
use Tassili\Tassili\Fields\TextInput;
use Tassili\Tassili\Filters\FilterText;
use Tassili\Tassili\Actions\Action;

class ListingController extends Controller
{
    private string \$tassiliPanel = '$panel';
    private string \$modelClass = 'App\Models\\$a';
    private ListingUtility \$utility;
    
    public function __construct(Request \$request)
    {
        // Initialisation de la classe utilitaire avec les paramètres
        \$this->utility = new ListingUtility([
            'tassiliPanel' => \$this->tassiliPanel,
            'tassiliDataModelLabel' => '$b',
            'tassiliDataModelTitle' => 'Create $b',
            'tassiliDataRouteListe' => '/$panel/$c',
            'tassiliDataUrlCreate' => '/$panel/$c/create',
            'tassiliModelClass' => \$this->modelClass,
            'tassiliModelClassName' => '$a',
            'paginationPerPageList' => [10, 20, 30, 40, 50],
            'orderByFieldList' => ['id'],
            'orderDirectionList' => ['asc', 'desc'],
            'urlDelete' => '/$panel/$c/delete',
        ]);

        \$this->customFilterList();
        \$this->initAction();
        \$this->initCustom();
    }


     private function customFilterList(): void
    {
        \$this->utility->filterList([
            FilterText::make('name'),
            FilterText::make('ville'),
        ]);
    }


    private function initAction(): void
    {
        \$this->utility->ActionList([
            Action::make('action1')
                ->params([
                    'label' => 'Ajouter',
                    'icon' => 'description',
                    'class' => 'text-white',
                    'url' =>'/$panel/$c/action1' ,
                    'confirmation' => 'Are you sure to change records',
                    'message' => 'Records changed'
                ])
        ]);
    }

     private function initCustom(): void
    {
        \$this->utility->CustomActionForm([
            'url' => '/$panel/$c/custom1',
            'icon' => 'edit',
            'text' => 'Qte',
            'class' => 'text-white',
            'confirm' => 'Are you sure to change record?',
        ])->form([
            TextInput::make('name')
        ]);
    }


     private function initQuery(\$query, Request \$request): void
    {
        if (\$request->filled('name')) {
           //  \$query->where('name', \$request->name);
        }
    }

      #[Post('$panel/$c/action1', middleware: ['tassili.auth'])]
    public function action1(Request \$request)
    {
        \$this->modelClass::whereIn('id', \$request->actionIds)->update([
            'name' => 'Fiat',
        ]);
    }

     #[Post('$panel/$c/custom1', middleware: ['tassili.auth'])]
    public function custom1(Request \$request)
    {
        \$request->validate(['name' => ['required']]);

        \$this->utility->tassiliRecord = \$this->modelClass::find(\$request->id);

        if (\$this->utility->tassiliRecord !== null) {
            \$this->utility->updateRecord(\$request);
            \$this->utility->tassiliRecord->save();
        }
    }

    
    #[Post('$panel/$c/delete', middleware: ['tassili.auth'])]
    public function delete(Request \$request)
    {
        \$this->modelClass::destroy(\$request->id);
    }
        
     #[Get('$panel/$c', middleware: ['tassili.auth'])]
    public function index(Request \$request)
    {
        \$this->utility->initializeQuery(
          \$this->modelClass,\$request,fn(\$query, \$req) => \$this->initQuery(\$query, \$req)
        );
        \$data = \$this->utility->getInertiaData();
        \$data['sessionFilter'] = [/*'search','orderByField','orderDirection','paginationPerPage'*/];

        return Inertia::render('TassiliPages/$panelCamel/Crud/$a/Listing', \$data);
    }
   
  

    
    
    
}
";

      return $this->piece3;
      }



    public function getPiece4($a,$b,$c,$panel,$panelCamel) {

         $this->piece4 = "<?php

namespace App\Http\Controllers\Tassili\\$panelCamel\Crud\\$a\Customs;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;


class Custom1Controller extends Controller
{
      private string \$tassiliPanel = '$panel';
      public function __construct()
    {
        config(['inertia.ssr.enabled' => false]); // SSR desactivated
    } 

 //  #[Get('$panel/$c/page1',middleware : ['tassili.auth'])]
    public function index(Request \$request)
    {
 
        return Inertia::render('TassiliPages/$panelCamel/Crud/$a/Customs/Custom1',
        [
          'user' => \Illuminate\Support\Facades\Auth::user(),
          'routes' =>  \Tassili\Tassili\Models\TassiliCrud::where('active',true)
                       ->where('panel',\$this->tassiliPanel)->get(),
          'tassiliUrlStorage' => config('tassili.storage_url') ,
        ]
    );
    }
}
         
         ";

    return $this->piece4;

    }




}