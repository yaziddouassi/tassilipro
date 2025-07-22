<?php
namespace App\Http\Controllers\Tassili\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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

   public   $tassiliPanel = 'admin' ;

   #[Get('admin',middleware : ['auth'])]
    public function index(Request $request)
    {
        
        return Inertia::render('TassiliPages/Admin/Dashboard/Dashboard', [
            
            'user' => \Illuminate\Support\Facades\Auth::user(),
            'routes' =>  \Tassili\Free\Models\TassiliCrud::where('active',true)->get(),
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

    
}