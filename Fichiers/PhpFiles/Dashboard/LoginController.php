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
use Spatie\RouteAttributes\Attributes\Post;


class LoginController extends Controller
{

   public   $tassiliPanel = 'admin' ;
   public   $urlToRedirect = '/admin' ;
   public   $urlValidation = '/admin/login/validate' ;

   #[Post('admin/login/validate')]
   public function validate(Request $request)
    {
         $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect($this->urlToRedirect);
        }

        return back()->withErrors([
            'email' => 'Wrong Credentials.',
        ])->onlyInput('email');
    }

   #[Get('admin/login')]
    public function index(Request $request)
    {
        
        return Inertia::render('TassiliPages/Admin/Dashboard/Login', [
            'tassiliPanel' => $this->tassiliPanel,
            'company' => config('tassili.company') ,
            'urlValidation' => $this->urlValidation ,
        ]);
    }

    
}