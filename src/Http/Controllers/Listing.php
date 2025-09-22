<?php

namespace Tassili\Tassili\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class Listing extends Controller
{
    
    public $tassiliSettings = [] ;
    public $tassiliFormList = [] ;
    public $tassiliRecord = null;
    public $allFilters = ['search' => 'search', 'paginationPerPage' => 'paginationPerPage',
     'orderByField' => 'orderByField', 'orderDirection' => 'orderDirection'];
    public $customFilters = [] ;
    public $tabFilterFields = [];
    public $tabFilterLabels = [];
    public $tabFilterTypes = [];
    public $tabFilterOptions = [];
    public $search = '' ;
    public $queryFilter;
    public $tables;
    public $groupActions = [];
    public $customActionUrlTemoin ='';
    public $arrayTypes1 = ['Text','Date','Number','Hidden','Select','Radio','Textarea'];
    public $arrayTypes2 = ['Quill'];
    public $arrayTypes4 = ['FileEdit'];
    public $arrayTypes5 = ['MultipleFileEdit'];
    public $arrayTypes6 = ['CheckboxList'];
    public $arrayTypes7 = ['Checkbox'];
    public $arrayTypes8 = ['Password'];
    public $arrayTypes9 = ['Repeater'];

    function __construct(Request $request) {

        config(['inertia.ssr.enabled' => false]); // SSR desactivated

        $this->tassiliSettings['tassiliDataModelLabel'] =  $this->tassiliDataModelLabel ;
        $this->tassiliSettings['tassiliDataModelTitle'] =  $this->tassiliDataModelTitle ;
        $this->tassiliSettings['tassiliDataRouteListe'] =  $this->tassiliDataRouteListe ;
        $this->tassiliSettings['tassiliDataUrlCreate'] =  $this->tassiliDataUrlCreate ;
        $this->tassiliSettings['tassiliModelClass'] =  $this->tassiliModelClass ;
        $this->tassiliSettings['tassiliModelClassName'] =  $this->tassiliModelClassName ;
        $this->tassiliSettings['paginationPerPageList'] =  $this->paginationPerPageList ;
        $this->tassiliSettings['orderByFieldList'] =  $this->orderByFieldList ;
        $this->tassiliSettings['orderDirectionList'] =  $this->orderDirectionList ;
        $this->tassiliSettings['urlDelete'] =  $this->urlDelete ;
        $this->customFilterList($request);
        $this->initAction($request);
        $this->initCustom($request);
    }


    public function filterList(array $fields): void
      {

        foreach ($fields as $field) {

            $field->registerTo($this);   
 
       }

     }


      public function ActionList(array $fields): void
      {

        foreach ($fields as $field) {

            $field->registerTo($this);   
 
       }

     }



     public function CustomActionForm(array $settings)
      {

       
        $this->tassiliFormList[$settings['url']]['info'] = $settings ;
        $this->tassiliFormList[$settings['url']]['info']['wizardActive'] = 'no' ;
        $this->tassiliFormList[$settings['url']]['info']['wizard'] = [] ;

        $this->customActionUrlTemoin = $settings['url'] ;

        return $this ;

     }


     public function form(array $fields)
    {
    foreach ($fields as $field) {

         $field->registerToCustomAction($this);   
    }

    return $this ;
    }


     public function wizard($wizard)
    {
       $this->tassiliFormList[$this->customActionUrlTemoin]['info']['wizard'] = $wizard ;
       $this->tassiliFormList[$this->customActionUrlTemoin]['info']['wizardActive'] = 'yes' ;
       return $this ;
    }
      



    public function initQuery(Request $request)
     {
       // Méthode volontairement vide, pour être overridée par les enfants
     }



     public function allInit($request) {

        $paginationPerPage = $this->paginationPerPageList[0];
        $orderByField = $this->orderByFieldList[0];
        $orderDirection = $this->orderDirectionList[0];

        if ($request->filled('paginationPerPage')) {
            if (in_array($request->paginationPerPage, $this->paginationPerPageList)) {
                $paginationPerPage = $request->paginationPerPage ;
                 }
        }

        if ($request->filled('orderByField')) {
            if (in_array($request->orderByField, $this->orderByFieldList)) {
                $orderByField = $request->orderByField ;
                 }
        }

        if ($request->filled('orderDirection')) {
            if (in_array($request->orderDirection, $this->orderDirectionList)) {
                $orderDirection = $request->orderDirection ;
                 }
        }


        
        $this->customFilters['Fields'] =  $this->tabFilterFields ;
        $this->customFilters['Labels'] =  $this->tabFilterLabels ;
        $this->customFilters['Types'] =  $this->tabFilterTypes ;
        $this->customFilters['Options'] =  $this->tabFilterOptions;
        $this->queryFilter = $this->tassiliModelClass::select('*');
        $this->initQuery($request);
       
    
        $this->tables = $this->queryFilter->orderBy($orderByField,$orderDirection)
                             ->paginate($paginationPerPage)
                             ->appends($request->except('page'));
    }




    public function updateRecord(Request $request) {


      $url = $request->urlValidationurlValidationurlValidationTassili17485RRY4R4RD9448RK48K4RFRFIRU;

       foreach ($request->all() as $key => $value) {

         if (array_key_exists($key, $this->tassiliFormList[$url]['fields'])) {
            if($this->tassiliFormList[$url]['fields'][$key] &&  $this->tassiliFormList[$url]['fields'][$key]['options']['noDatabase'] == 'no') {

               if (in_array($this->tassiliFormList[$url]['fields'][$key]['type'], $this->arrayTypes1) || 
                    in_array($this->tassiliFormList[$url]['fields'][$key]['type'], $this->arrayTypes2) ) {

                     $this->tassiliRecord[$key]  = $value ;
                }


                 elseif (in_array($this->tassiliFormList[$url]['fields'][$key]['type'], $this->arrayTypes6)) {
                $this->tassiliRecord[$key] = is_array($value) ? json_encode($value) : json_encode(explode(',', $value));
               }


                 elseif (in_array($this->tassiliFormList[$url]['fields'][$key]['type'], $this->arrayTypes7)) {
                
                    if($value == 'true') {
                        $value2 = true;
                     }
                     if($value == 'false') {
                        $value2 = false;
                     } 
                     $this->tassiliRecord[$key] = $value2;    
               }

                 elseif (in_array($this->tassiliFormList[$url]['fields'][$key]['type'], $this->arrayTypes8)) {
                  if($value) {
                    $this->tassiliRecord[$key] = Hash::make($value);
                  }
               }


               elseif (in_array($this->tassiliFormList[$url]['fields'][$key]['type'], $this->arrayTypes9)) {
    $cleanedRepeater = [];
    $tabTemp = ['Text','Date','Number','Hidden','Select','Radio','Textarea','Quill','Checkbox'] ;

    foreach ($value as $repeaterItem) {
        $cleanedItem = [];

        foreach ($repeaterItem as $subKey => $subValue) {
             $subType = $this->tassiliFormList[$url]['fields'][$key]['fields'][$subKey]['type'] ?? null;

            if ($subType === 'CheckboxList') {
                // ✅ NE PAS utiliser json_encode ici
                $cleanedItem[$subKey] = is_array($subValue) ? $subValue : explode(',', $subValue);
            }
            else if (in_array($subType, $tabTemp)){
                $cleanedItem[$subKey] = $subValue;
                if($subValue === null) {
                    $cleanedItem[$subKey] = '';
                }
            }


        }

        $cleanedRepeater[] = $cleanedItem;
    }

    // ✅ On encode seulement à la fin
    $this->tassiliRecord[$key] = json_encode($cleanedRepeater);
} 



               if (in_array($this->tassiliFormList[$url]['fields'][$key]['type'], $this->arrayTypes4)) {
                    $dossier = $this->tassiliFormList[$url]['fields'][$key]['options']['storage_folder'];
                    $dossierStorage = 'uploads/' . $dossier ; 
                    if ($request->hasFile($key)) {
                        $file = $request->file($key);
                        $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
                        $file->storeAs($dossierStorage, $uniqueName, config('tassili.storage_disk'));
                        $path = $dossierStorage . '/' . $uniqueName ;
                        $this->tassiliRecord[$key] = $path;
                    }
                }  



                 elseif (in_array($this->tassiliFormList[$url]['fields'][$key]['type'], $this->arrayTypes5)) { 
                 $tab1 = json_decode($request->input($key . '_newtab')) ;
                 $dossier = $this->tassiliFormList[$url]['fields'][$key]['options']['storage_folder'];
                 $dossierStorage = 'uploads/' . $dossier ;
                 if($value) {
                    foreach ($value as $file) {
                      
                        $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
                        $path = $file->storeAs($dossierStorage, $uniqueName, config('tassili.storage_disk'));
                        array_push($tab1, $path);
                    }
                 }
                 
                $this->tassiliRecord[$key] = json_encode($tab1) ;
                   
                }






            }
        }

       }

    }


    
    
}