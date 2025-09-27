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


class WizardUpdate extends Controller
{
    
    public $tassiliSettings = [] ;
    public $tassiliFields = [] ;
    public $tassiliWizardInfo = [] ;
    public $tassiliRecord = null;
    public $tassiliRecordInput ;
   
    public $arrayTypes1 = ['Text','Date','Number','Hidden','Select','Radio','Textarea','Signature'];
    public $arrayTypes2 = ['Quill'];
    public $arrayTypes4 = ['FileEdit'];
    public $arrayTypes5 = ['MultipleFileEdit'];
    public $arrayTypes6 = ['CheckboxList'];
    public $arrayTypes7 = ['Checkbox'];
    public $arrayTypes8 = ['Password'];
    public $arrayTypes9 = ['Repeater'];


    function __construct() {

        config(['inertia.ssr.enabled' => false]); // SSR desactivated

        $this->tassiliSettings['tassiliDataModelLabel'] =  $this->tassiliDataModelLabel ;
        $this->tassiliSettings['tassiliDataModelTitle'] =  $this->tassiliDataModelTitle ;
        $this->tassiliSettings['tassiliDataRouteListe'] =  $this->tassiliDataRouteListe ;
        $this->tassiliSettings['tassiliDataUrlCreate'] =  $this->tassiliDataUrlCreate ;
        $this->tassiliSettings['tassiliModelClass'] =  $this->tassiliModelClass ;
        $this->tassiliSettings['tassiliModelClassName'] =  $this->tassiliModelClassName ;
        $this->tassiliSettings['tassiliValidationUrl']=  $this->tassiliValidationUrl ;
        
        $this->initField();
    }


     public function form(array $fields)
    {
    foreach ($fields as $field) {

          $field->updateTo($this);  
    }

    return $this ;
    }


     public function tassilicheckRecord(Request $request)
{
    $Record = $this->tassiliModelClass::find($request->id);
    
    if ($Record === null) {
        return redirect($this->tassiliDataRouteListe); // Return the redirection
    }

    $this->tassiliRecordInput = new Collection();
    $this->tassiliRecordInput = $Record;

    return null; // Return null to indicate no redirection needed
}




    public function updateRecord(Request $request) {

       foreach ($request->all() as $key => $value) {

         if (array_key_exists($key, $this->tassiliFields)) {
            if($this->tassiliFields[$key] &&  $this->tassiliFields[$key]['options']['noDatabase'] == 'no') {

               if (in_array($this->tassiliFields[$key]['type'], $this->arrayTypes1) || 
                    in_array($this->tassiliFields[$key]['type'], $this->arrayTypes2)  ) 
                    {
                     $this->tassiliRecord[$key]  = $value ;
                }

                elseif (in_array($this->tassiliFields[$key]['type'], $this->arrayTypes6)) {
                $this->tassiliRecord[$key] = is_array($value) ? json_encode($value) : json_encode(explode(',', $value));
               }


                 elseif (in_array($this->tassiliFields[$key]['type'], $this->arrayTypes7)) {
                
                    if($value == 'true') {
                        $value2 = true;
                     }
                     if($value == 'false') {
                        $value2 = false;
                     } 
                     $this->tassiliRecord[$key] = $value2;    
               }


                elseif (in_array($this->tassiliFields[$key]['type'], $this->arrayTypes8)) {
                  if($value) {
                    $this->tassiliRecord[$key] = Hash::make($value);
                  }
               }
               

                  elseif (in_array($this->tassiliFields[$key]['type'], $this->arrayTypes9)) {
    $cleanedRepeater = [];
    $tabTemp = ['Text','Date','Number','Hidden','Select','Radio','Textarea','Quill','Checkbox'] ;

    foreach ($value as $repeaterItem) {
        $cleanedItem = [];

        foreach ($repeaterItem as $subKey => $subValue) {
            $subType =  $this->tassiliFields[$key]['fields'][$subKey]['type'] ?? null;

            if ($subType === 'CheckboxList') {
                // ✅ NE PAS utiliser json_encode ici
                $cleanedItem[$subKey] = is_array($subValue) ? $subValue : explode(',', $subValue);
            }
            else if (in_array($subType, $tabTemp)) {
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


          if (in_array($this->tassiliFields[$key]['type'], $this->arrayTypes4)) {
                    $dossier = $this->tassiliFields[$key]['options']['storage_folder'];
                    $dossierStorage = 'uploads/' . $dossier ;
                    
                    if ($request->hasFile($key)) {
                        $file = $request->file($key);
                        $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
                        $file->storeAs($dossierStorage, $uniqueName, config('tassili.storage_disk'));
                        $path = $dossierStorage . '/' . $uniqueName ;
                        $this->tassiliRecord[$key] = $path;
                    }
                }  


            elseif (in_array($this->tassiliFields[$key]['type'], $this->arrayTypes5)) { 
                 $tab1 = json_decode($request->input($key . '_newtab')) ;
                 $dossier = $this->tassiliFields[$key]['options']['storage_folder'];
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





    public function checkRecord(Request $request)
{
    return $this->tassilicheckRecord($request) ;
}


    public function initFieldAgain(Request $request) {

      foreach ($this->tassiliFields as $key => $value) {

            if($value['options']['noDatabase'] === 'no') {

                if (in_array($value['type'], $this->arrayTypes1) || 
                    in_array($value['type'], $this->arrayTypes2) ||
                    in_array($value['type'], $this->arrayTypes7)  
                   ) {

                   $this->tassiliFields[$key]['value'] = $this->tassiliRecordInput[$key];
                }

                elseif (in_array($value['type'], $this->arrayTypes6)) {
                   $this->tassiliFields[$key]['value'] = json_decode($this->tassiliRecordInput[$key], true); 
               
                }

                elseif (in_array($value['type'], $this->arrayTypes4)) {
                   $this->tassiliFields[$key]['options']['urlRecord'] = $this->tassiliRecordInput[$key]; 
                }

                elseif (in_array($value['type'], $this->arrayTypes5)) {
                   $this->tassiliFields[$key]['options']['existingFiles'] = json_decode($this->tassiliRecordInput[$key], true); 
                }

                elseif (in_array($value['type'], $this->arrayTypes9)) {
                    $this->tassiliFields[$key]['value'] = json_decode($this->tassiliRecordInput[$key], true); 
                  
                }



            }
            
      }
    
     } 
    

    public function wizard($wizard)
    {
       $this->tassiliWizardInfo =  $wizard ;
       return $this ;
    }
    
    
}