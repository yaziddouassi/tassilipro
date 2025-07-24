<?php

namespace Tassili\Tassili\Utils;
use Illuminate\Support\Str;

class PanelPart
{

public $piece1;
public $piece2;    

public function getPiece1($panel ,$panelCamel,$middleware) {

$this->piece1 = "<?php
namespace App\Http\Controllers\Tassili\\$panelCamel\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Database\Eloquent\Collection;
use Spatie\RouteAttributes\Attributes\Get;


class DashboardController extends Controller
{

   public   \$tassiliPanel = '$panel' ;

   #[Get('$panel',middleware : ['$middleware'])]
    public function index(Request \$request)
    {
        
        return Inertia::render('TassiliPages/$panelCamel/Dashboard/Dashboard', [
            'tassiliPanel' => \$this->tassiliPanel,
            'user' => \Illuminate\Support\Facades\Auth::user(),
            'routes' =>  \Tassili\Tassili\Models\TassiliCrud::where('active',true)
                          ->where('panel',\$this->tassiliPanel)->get(),
            'chart1' => ['datas' => [mt_rand(1, 12),mt_rand(1, 12),mt_rand(1, 12)],
                         'labels'  => ['Jan','Fev','Mars'] ],
            'chart2' => ['datas' => [mt_rand(1, 12),mt_rand(1, 12),mt_rand(1, 12)],
                         'labels'  => ['Avil','Mai','jUIN'] ],
            'chart3' => ['datas' => [mt_rand(1, 12),mt_rand(1, 12),mt_rand(1, 12)],
                         'labels'  => ['Juillet','Aout','septembre'] ],
            'widget1' => ['value' =>  mt_rand(1, 50),
                         'title'  => 'Sales of month',
                         'icon'  => 'account_circle'], 
            'widget2' => ['value' =>  mt_rand(1, 50),
                         'title'  => 'Number of Visiteurs',
                         'icon'  => 'account_circle'],              
            'widget3' => ['value' =>  mt_rand(1, 50),
                         'title'  => 'Bests Products',
                         'icon'  => 'account_circle'],
            'widget4' => ['value' =>  mt_rand(1, 50),
                         'title'  => 'Sales of month',
                         'icon'  => 'account_circle'], 
            'widget5' => ['value' =>  mt_rand(1, 50),
                         'title'  => 'Number of Visiteurs',
                         'icon'  => 'account_circle'],              
            'widget6' => ['value' =>  mt_rand(1, 50),
                         'title'  => 'Bests Products',
                         'icon'  => 'account_circle'],                

        ]);
    }

    
}";

    return $this->piece1;
}

public function getPiece2($panel ,$panelCamel,$middleware) {


    $this->piece2 = "<?php
namespace App\Http\Controllers\Tassili\\$panelCamel\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Database\Eloquent\Collection;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;


class LoginController extends Controller
{

   public   \$tassiliPanel = '$panel' ;
   public   \$urlToRedirect = '/$panel' ;
   public   \$urlValidation = '/$panel/login/validate' ;

   #[Post('$panel/login/validate')]
   public function validate(Request \$request)
    {
         \$credentials = \$request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(\$credentials, \$request->boolean('remember'))) {
            \$request->session()->regenerate();

            return redirect(\$this->urlToRedirect);
        }

        return back()->withErrors([
            'email' => 'Wrong Credentials.',
        ])->onlyInput('email');
    }

   #[Get('$panel/login')]
    public function index(Request \$request)
    {
        
        return Inertia::render('TassiliPages/$panelCamel/Dashboard/Login', [
            'tassiliPanel' => \$this->tassiliPanel,
            'company' => config('tassili.company') ,
            'urlValidation' => \$this->urlValidation ,
        ]);
    }

    
}";

    return $this->piece2;
}



}