<?php

namespace App\Http\Controllers\Views\Backend;

use DB;
use Auth;
use Mail;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Mail\PasswordMail;

class UserController extends Controller
{
    /**
      * @var userRepository $userRepository
      * @var roleRepository $roleRepository
      */
     protected $userRepository;
     protected $roleRepository;

     /**
      * userController constructor.
      *
      * @param userRepository $userRepository
      * @param userRepository $userRepository
      */
     public function __construct(userRepository $userRepository, roleRepository $roleRepository)
     {
         $this->userRepository = $userRepository;
         $this->roleRepository = $roleRepository;
     }

     /**
      * Display a listing of the resource.
      * @return Response
      */
     public function index(Request $request)
     {
         $keywords = $request->keywords;
         $status_search = $request->status_search;
         $roles = Role::orderBy('name', 'desc')->get();

         $users = User::with('role')
                ->when('keywords', function($query) use ($keywords) {
                    return $query->whereHas('role', function ($q) use ($keywords) {
                        $q->where('firstname', 'like', '%'.$keywords.'%')
                        ->orWhere('lastname', 'like', '%'.$keywords.'%')
                        ->orWhere('status', 'like', '%'.$keywords.'%')
                        ->orWhere('name', 'like', '%'.$keywords.'%');
                    });
                })
                ->when($request->status_search, function ($q) use ($request) {
                    return $q->where('status', $request->status_search);
                })
                ->paginate(self::BACKEND_PAGINATE);

         return view('backend.users.index', compact('users', 'keywords', 'roles', 'status_search'));
     }

     /**
      * Show the form for creating a new resource.
      * @return Response
      */
     public function create()
     {
         $roles = Role::orderBy('name', 'desc')->get();
         $organizations = Organization::orderBy('name')->get();
         return view('backend.users.create', compact('roles', 'organizations'));
     }

     /**
      * Store a newly created resource in storage.
      * @param  Request $request
      * @return Response
      */
     public function store(Request $request)
     {
        try {
            $validator = Validator::make($request->all(), [
                   'firstname' => 'required',
                   'lastname' => 'required',
                   'mobile'  => 'required'
            ]);

            if($validator->fails())
               return redirect()->back()
                    ->withInput($request->all())
                    ->withErrors(['validator' => 'Nom, Prenom & Téléphone obligatoires']);

             $password = Hash::make('secret'); // default password: secret
             $pin = Hash::make(str_random(5));
             $api_token = str_random(60);
             $remember_token = str_random(10);

             $user = User::create([
                 'name'            => $request->name,
                 'firstname'       => $request->firstname,
                 'lastname'        => $request->lastname,
                 'password'        => $password,
                 'email'           => $request->email,
                 'address'         => $request->address,
                 'mobile'          => $request->mobile,
                 'position'        => $request->position,
                 'pin'             => $pin,
                 'organization_id' => $request->organization_id,
                 'role_id'         => $request->role_id,
                 'status'          => $request->status,
                 'api_token'       => $api_token,
                 'remember_token'  => $remember_token
             ]);

             if ($user){
                 //create a new token to be sent to the user.
                 DB::table('password_resets')->insert([
                     'email' => $user->email,
                     'token' => $user->api_token, //change 60 to any length you want
                     'created_at' => Carbon::now()
                 ]);

                 return redirect()->route('admin.users.edit', $user->code)
                     ->with('message', 'L\'utilisateur a bien été créé !');
             }

         } catch (\Exception $e) {
             return redirect()->back()
             ->withInput($request->all())
             ->withErrors($e->getMessage());
         }
     }

     /**
      * Show the specified resource.
      * @return Response
      */
     public function show($code)
     {
         $user = $this->userRepository->findByField('code',$code);

         if(is_null($user) ) {
             return abort(404);
         }

         return view('backend.users.show', compact('user'));
     }

     /**
      * Show the form for editing the specified resource.
      * @return Response
      */
     public function edit($code)
     {
         $user = User::where('code', $code)->first();
         $roles = Role::orderBy('name', 'desc')->get();
         $organizations = Organization::orderBy('name')->get();

         if (!$user) return redirect()->route('admin.users.index');

         return view('backend.users.edit', compact('user', 'roles', 'organizations'));
     }

     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, $id)
     {
         try
         {
             $user = User::find($id);

             if (!$user) return redirect()->route('admin.users');

             $user->name = $request->name ? $request->name : $user->name;
             $user->firstname = $request->firstname ? $request->firstname : $user->firstname;
             $user->lastname = $request->lastname ? $request->lastname : $user->lastname;
             $user->email = $request->email ? $request->email : $user->email;
             $user->address = $request->address ? $request->address : $user->address;
             $user->position = $request->position ? $request->position : $user->position;
             $user->mobile = $request->mobile ? $request->mobile : $user->mobile;
             $user->organization_id = $request->organization_id ? $request->organization_id : $user->organization_id;
             $user->role_id = $request->role_id ? $request->role_id : $user->role_id;
             $user->status = $request->status ? $request->status : $user->status;
             $user->save();

             return redirect()->route('admin.users.edit', $user->code)
                 ->with('message', 'L\'utilisateur a bien été mis à jour !');

         } catch (\Exception $e) {
             \Log::error($e->getMessage());
             return redirect()->back()
             ->withErrors($e->getMessage());
         }
     }

     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function blocked($id)
     {
         try
         {
             $user = User::find($id);

             if (!$user)
                return redirect()->route('admin.users.index')
                        ->with('message', 'L\'utilisateur n\'existe pas !');

             if($user->status == 'blocked'){
                 $user->status = 'active';
                 $user->update();

                 return redirect()->route('admin.users.edit', $user->code)
                     ->with('message', 'L\'utilisateur a bien été débloqué !');
             }

             if($user->status == 'active'){
                 $user->status = 'blocked';
                 $user->update();

                 return redirect()->route('admin.users.edit', $user->code)
                     ->with('message', 'L\'utilisateur a bien été bloqué !');
             }


         } catch (\Exception $e) {
             return redirect()->back()
                    ->withErrors($e->getMessage());
         }
     }

     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function reset(Request $request, $id)
     {
         try
         {
             $user = $this->userRepository->find($id);

             if (!$user)
                return redirect()->route('admin.users.edit', $user->id)->with('L\'utilisateur n\'a pas été trouvé !');

             if ($request->password === $request->confirmation) {
                 $user->password = bcrypt($request->password);
                 $user->update();

                 DB::table('password_resets')->insert([
                     'email'      => $user->email,
                     'token'      => $user->api_token,
                     'created_at' => Carbon::now()
                 ]);

                 return redirect()->route('admin.users.edit', $user->id)
                     ->withSuccess('Mot de passe a été mise à jour !');
             }

             if ($request->password !== $request->confirmation) {
                 return redirect()->route('admin.users.edit', $user->id)
                     ->withSuccess('Les mots passe sont différents !');
             }


         } catch (\Exception $e) {
             \Log::error($e->getMessage());
             return response()->json(['message'=>'internal server error'],500);
         }
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         try {
             $user = User::find($id);

             $delete = $user->delete();
             if ($delete) {
                 return redirect()->route('admin.users.index')
                        ->with('message', 'L\'utilisateur a bien été supprimé');
             }
         }
         catch (\Exception $e) {
             return redirect()->back()
                    ->withErrors($e->getMessage());
         }
     }

     public function sendPasswordResetToken(Request $request)
     {
        $user = User::where('email', $request->email)-first();
        if (!$user) return redirect()->back()->withErrors(['error' => '404']);

        //create a new token to be sent to the user.
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $user->api_token, //change 60 to any length you want
            'created_at' => Carbon::now()
        ]);

        $tokenData = DB::table('password_resets')
        ->where('email', $request->email)->first();

       $token = $tokenData->token;
       $email = $request->email; // or $email = $tokenData->email;

       /**
        * Send email to the email above with a link to your password reset
        * something like url('password-reset/' . $token)
        * Sending email varies according to your Laravel version. Very easy to implement
        */
        $this.sendPassword($user);
     }

     /**
     * Assuming the URL looks like this
     * http://localhost/password-reset/random-string-here
     * You check if the user and the token exist and display a page
     */

     public function showPasswordResetForm($token)
     {
         $tokenData = DB::table('password_resets')
         ->where('token', $token)->first();

         if ( !$tokenData ) return redirect()->to('home'); //redirect them anywhere you want if the token does not exist.
         return view('passwords.show');
     }

     public function resetPassword(Request $request, $email)
     {
         $password = $request->password;
         $tokenData = DB::table('password_resets')->where('email', $email)->first();

         $user = User::where('email', $tokenData->email)->first();
         if (!$user) return redirect()->route('admin.users.edit', $user->id)->with('L\'utilisateur n\'a pas été trouvé !');

         $user->password = bcrypt($password);
         $user->update(); //or $user->save();

         $this->sendPassword($user);

     }

     /**
      * Send pass confirm to email
      * @param  [type] $passwordMail [description]
      * @return [type]             [description]
      */
     private function sendPassword ($user)
     {
         Mail::to($user->email)->queue(new PasswordMail($user));
     }

}
