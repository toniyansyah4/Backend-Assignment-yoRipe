<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hashids\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends ApiController
{
    /**
     * Handle account login request
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(null, 'Sorry, Login Failed', 200);
        }
        $user = User::where('email', strtolower($request->email))->first();
        if ($user == null || !$user) {
            return $this->errorResponse(null, 'Sorry, Login Failed', 200);
        }
        $user->makeVisible(['email_verified_at', 'password']);
        if (!$user->email_verified_at) {
            return $this->errorResponse(null, 'Sorry, Login Failed', 200);
        }
        if (!Hash::check($request->password, $user->password)) {
            return $this->errorResponse(null, 'Sorry, Login Failed', 200);
        }
        // Send an internal API request to get an access token
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();

        // Make sure a Password Client exists in the DB
        if (!$client) {
            return $this->errorResponse(
                null,
                'Laravel Passport is not setup properly',
                200
            );
        }
        $data = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => strtolower($request->email),
            'password' => $request->password,
        ];

        $request = Request::create('/oauth/token', 'POST', $data);

        $response = app()->handle($request);

        // Get the data from the response
        $data = json_decode($response->getContent());

        $hashids = new Hashids('', 9);

        $user = User::where('id', $user->id)->first();
        $fields = [
            'id' => $hashids->encode($user->id),
            'name' => $user->name,
            'email' => $user->email,
            'token' => [
                'access_token' => $data->access_token,
                'expires_in' => $data->expires_in,
                'refresh_token' => $data->refresh_token,
            ],
        ];

        // $auditFields = [
        //     'user_type' 			=> "App\Models\User",
        //     'user_id'				=> $user->id,
        //     'event'					=> 'login',
        //     'auditable_type' 	    => "App\Models\User",
        //     'auditable_id' 		    => $user->id,
        //     'old_values'			=> [],
        //     'new_values'			=> $fields,
        //     'url'					=> $request->url(),
        //     'ip_address'			=> $request->getClientIp(),
        //     'user_agent'			=> $request->userAgent(),
        //     'tags'					=> 'access'
        // ];
        // Audit::create($auditFields);
        // Format the final response in a desirable format
        return $this->successResponse($fields, 'Success login');
    }

    /**
     * Handle response after user authenticated
     *
     * @param Request $request
     * @param Auth $user
     *
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended();
    }
}
