<?php

namespace Tassili\Tassili\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TassiliCreate extends Controller
{
    
    public $tassiliSettings = [] ;
    public $tassiliFields = [] ;
    public $tassiliRecord = null;
    public $arrayTypes1 = ['Text','Date','Number','Hidden','Select','Radio','Textarea'];
    public $arrayTypes2 = ['Quill'];
    public $arrayTypes3 = ['File'];
    public $arrayTypes5 = ['MultipleFile'];
    public $arrayTypes6 = ['CheckboxList'];
    public $arrayTypes7 = ['Checkbox'];
    public $arrayTypes8 = ['Password'];
    public $arrayTypes9 = ['Repeater'];

    function __construct() {

        $this->tassiliSettings['tassiliShowOther'] = $this->tassiliShowOther ;
        $this->tassiliSettings['tassiliDataModelLabel'] =  $this->tassiliDataModelLabel ;
        $this->tassiliSettings['tassiliDataModelTitle'] =  $this->tassiliDataModelTitle ;
        $this->tassiliSettings['tassiliDataRouteListe'] =  $this->tassiliDataRouteListe ;
        $this->tassiliSettings['tassiliDataUrlCreate'] =  $this->tassiliDataUrlCreate ;
        $this->tassiliSettings['tassiliModelClass'] =  $this->tassiliModelClass ;
        $this->tassiliSettings['tassiliModelClassName'] =  $this->tassiliModelClassName ;
        $this->tassiliSettings['tassiliValidationUrl']=  $this->tassiliValidationUrl ;
        $this->initField();
    }


     public function form(array $fields): void
    {
    foreach ($fields as $field) {

         $field->registerTo($this);  
         
    }

    }


    public function createRecord(Request $request) {

       foreach ($request->all() as $key => $value) {

        if (array_key_exists($key, $this->tassiliFields)) {
            if($this->tassiliFields[$key]) {

               if (in_array($this->tassiliFields[$key]['type'], $this->arrayTypes1) || 
                    in_array($this->tassiliFields[$key]['type'], $this->arrayTypes2)   ) {

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

    foreach ($value as $repeaterItem) {
        $cleanedItem = [];

        foreach ($repeaterItem as $subKey => $subValue) {
            $subType =  $this->tassiliFields[$key]['fields'][$subKey]['type'] ?? null;

            if ($subType === 'CheckboxList') {
                // ✅ NE PAS utiliser json_encode ici
                $cleanedItem[$subKey] = is_array($subValue) ? $subValue : explode(',', $subValue);
            }
            else {
                $cleanedItem[$subKey] = $subValue;
            }


        }

        $cleanedRepeater[] = $cleanedItem;
    }

    // ✅ On encode seulement à la fin
    $this->tassiliRecord[$key] = json_encode($cleanedRepeater);
}


             elseif (in_array($this->tassiliFields[$key]['type'], $this->arrayTypes5)) {
                    // Handle multiple file uploads
                    $temp = [];
                    if ($request->hasFile($key)) {
                        foreach ($request->file($key) as $file) {
                            $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
                            $file->storeAs('uploads', $uniqueName, config('tassili.storage_disk'));
                            $path = 'uploads/' . $uniqueName ;
                            $temp[] = $path;
                        }
                    }
                    $this->tassiliRecord[$key] = json_encode($temp);
                }



             if (in_array($this->tassiliFields[$key]['type'], $this->arrayTypes3)) {
                    // Handle single file uploads
                    if ($request->hasFile($key)) {
                        $file = $request->file($key);
                        $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
                        $file->storeAs('uploads', $uniqueName, config('tassili.storage_disk'));
                        $path = 'uploads/' . $uniqueName ;
                        $this->tassiliRecord[$key] = $path;
                    }
                }  






            }
        }



      






       }

    }
   


    
}