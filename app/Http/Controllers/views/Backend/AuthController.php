<?php

namespace App\Http\Controllers\Views\Backend;

use Auth;
use DB;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;


class AuthController extends Controller
{
    use ResetsPasswords,SendsPasswordResetEmails;

    /**
     * @var UserRepository $userRepository
     */
    protected $userRepository;

    /**
     * AuthController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Show the form for sign in .
     *
     * @return \Illuminate\Http\Response
     */
    public function login (Request $request)
    {
        return view('backend.auth.login');


    }

     /**
     * Sign out.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout ()
    {
        if (Auth::check()) {
            session()->flush();
            Auth::logout();
        }
        return redirect()->route('admin.login');
    }

     /**
     * Sign in
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doLogin (Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'password'  => 'required|min:6',
                'email'     => 'required|email'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), self::HTTP_BADREQUEST);
            }

            $email = request('email');
            $password = request('password');

            if ( Auth::attempt(['email' => $email, 'password' => $password, 'status' => ['active','pending']], true) ) {
                $user = Auth::user();

                return response()->json([
                    'user' => $user,
                    'route' => redirect()->intended('admin')->getTargetUrl()
                ]);
            }
            return response()->json('Please check your credentials', self::HTTP_BADREQUEST);
        }
        catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json($e->getMessage(), self::HTTP_ERROR);
        }
    }

    /**
     * forgotPassword
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword ()
    {
        return view('backend.auth.forgot');
    }

    /**
     * sendLink
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendLink(Request $request)
    {
        $email = request('email');
        $user=$this->userRepository->findByField('email',$email);
        if (!$user) {
            return response()->json([
                    'type' => 'error',
                    'message' => 'user not found'
                ]);
        }
        if (!$user->isAdmin()) {
         return response()->json([
                    'type' => 'error',
                    'message' => 'the user is not admin'
                ]);
        }

        $this->sendResetLinkEmail($request);

        return response()->json([
                    'type' => 'success',
                    'message' => 'We just emailed you a link Please check your email and click the secure link'
                ]);
    }

    /**
     * showResetForm
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showResetForm ($token)
    {
        $email=null;

        $items= DB::table('password_resets')->get();

        foreach ($items as $item) {
            if($this->broker()->getRepository()->getHasher()->check($token, $item->token)){
                $email=$item->email;
            }
        }

        return view('backend.auth.reset', compact('email','token'));
    }

    /**
     * doResetPassword
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doResetPassword (Request $request)
    {

       $this->reset($request);

       return redirect()->back()->with('message', 'Le mot de passe a été mis à jour');
    }


    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }




}
