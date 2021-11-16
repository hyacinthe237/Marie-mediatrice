<?php

namespace App\Http\Controllers\views\front;

use Auth;
use Hash;
use App\Models\User;
use App\Models\Login;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function viewLogin () {
        if (Auth::guest()) {
            return view('front.auth.login');
        } return redirect()->route('home');
    }


    public function signout ()
    {
        try
        {
            if (Auth::check()) Auth::logout();
            return response()->json('OK');
        }
        catch(Exception $e) {
            return response()->json($e->getMessage(), self::HTTP_ERROR);
        }
    }


    public function signin (Request $request)
    {
        try
        {
            $rules = [
                'mobile' => 'required',
                'pin'    => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json(trans('app.all_fields_required'), self::HTTP_BADREQUEST);
            }

            // check user's status
            $user = User::where('mobile', $request->mobile)->first();
            if (!$user) {
                return response()->json(trans('app.account_not_found'), self::HTTP_ERROR);
            }

            if ($user->status === 'suspended') {
                return response()->json(trans('app.account_suspended'), self::HTTP_BADREQUEST);
            }

            if (Hash::check($request->pin, $user->pin)) {
                Auth::login($user, true);
                $this->saveLogin($user->id, $request->ip());

                return response()->json('OK');
            }

            return response()->json(trans('app.check_credentials'), self::HTTP_ERROR);
        }
        catch(Exception $e) {
            return response()->json($e->getMessage(), self::HTTP_ERROR);
        }
    }



    /**
 	 *  Save user's info
	 */
    private function saveLogin($id, $ip)
    {
        $agent = new Agent();

        Login::create([
            'user_id'   => $id,
            'ip'        => $ip,
            'device'    => $agent->device(),
            'platform'  => $agent->platform(),
            'browser'   => $agent->browser()
        ]);
    }
}
