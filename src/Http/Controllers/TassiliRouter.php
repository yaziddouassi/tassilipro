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

class TassiliRouter extends Controller
{

   public function __construct()
    {
        config(['inertia.ssr.enabled' => false]); // SSR desactivated
    } 
    
   public function update(Request $request)
    {

    $record =  \Tassili\Tassili\Models\TassiliCrud::find($request->id);
 
      if ($record === null) {
            return redirect('/tassili/router');
      }
        return Inertia::render('TassiliDev/Update',[
             'record' => $record ,
        ]);
    }

  public function create(Request $request)
    {

        return Inertia::render('TassiliDev/Create',
        [
       'listModels' => config('tassili.modelList') ,
       'panelList'  => config('tassili.panelList')
        ]);
    }

  public function index(Request $request)
    {
    
if ($request->filled('panel')) {
     $panels = \Tassili\Tassili\Models\TassiliCrud::where('panel',$request->panel)
             ->paginate(10) ;
}

if (!$request->filled('panel')) {
     $panels = \Tassili\Tassili\Models\TassiliCrud::paginate(10) ;
}

        return Inertia::render('TassiliDev/Index',[
         'panelList' => config('tassili.panelList'),
         'panels' => $panels ,
        ]);
    }
    

    public function updateActive(Request $request)
    {  
        \Tassili\Tassili\Models\TassiliCrud::where('id',$request->id)->update([
            'active' => (bool) $request->active,
        ]);
    } 
   
    public function delete(Request $request)
    {  
        \Tassili\Tassili\Models\TassiliCrud::destroy($request->id);
    }  



    public function updator(Request $request)
    {
         $request->validate([
      'icon' => ['required'],
      'label' => ['required'],
      'active' => ['required'],
    ]);

    
    \Tassili\Tassili\Models\TassiliCrud::where('id',$request->id)->update([
      'icon' => $request->icon,
      'label' => $request->label ,
      'active' => (bool) $request->active,
  ]);
    }

    public function creator(Request $request)
    {
        $request->validate([
            'selected' => ['required'],
            'panel' => ['required'],
         ]);
        
        foreach ($request->selected as $key => $value) {

            $transformString = new \Tassili\Tassili\Utils\TransformString();
            $crud = new \Tassili\Tassili\Models\TassiliCrud() ;
            $crud->panel  =  $request->panel;
            $crud->model  =  $value;
            $crud->label  = $transformString->transformLink($value) ;
            $crud->route  = '/' . $request->panel .'/' .  $transformString->transformUrl($value) ;
            $crud->icon  = 'description' ;
            $crud->active  = true ;
            $crud->save()  ;
        }
    }


    public function logout(Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    
        return redirect('/');
    }



}