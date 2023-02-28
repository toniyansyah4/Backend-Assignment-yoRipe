<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends ApiController
{
    /**
     * Display register page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Handle account registration request
     *
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $oldPassword = $request->password;
        $newPassword = $request->password_confirmation;

        $uppercase = preg_match('@[A-Z]@', $newPassword);
        $lowercase = preg_match('@[a-z]@', $newPassword);
        $number = preg_match('@[0-9]@', $newPassword);
        $special = preg_match(
            '/^(?=(?:.*[A-Z]){1,})(?=(?:.*[a-z]){1,})(?=(?:.*\d){1,})(?=(?:.*[!@#$%^&*()\-_=+{};:,<.>]){1,})(?!.*(.)\1{2})([A-Za-z0-9!@#$%^&*()\-_=+{};:,<.>]{8,})$/',
            $newPassword
        );
        $length = strlen($newPassword) >= 8;

        if (!$uppercase) {
            return response()->json([
                'success' => false,
                'statuscode' => 400,
                'message' => 'Password needs at least 1 uppercase letter',
                'error' => '',
                'data' => null,
            ]);
        } elseif (!$lowercase) {
            return response()->json([
                'success' => false,
                'statuscode' => 400,
                'message' => 'Password needs at least 1 lowercase letter',
                'error' => '',
                'data' => null,
            ]);
        } elseif (!$number) {
            return response()->json([
                'success' => false,
                'statuscode' => 400,
                'message' => 'Password needs at least 1 number',
                'error' => '',
                'data' => null,
            ]);
        } elseif (!$length) {
            return response()->json([
                'success' => false,
                'statuscode' => 400,
                'message' => 'Password is too short (minimum is 8 characters)',
                'error' => '',
                'data' => null,
            ]);
        } elseif (!$special) {
            return response()->json([
                'success' => false,
                'statuscode' => 400,
                'message' => 'Password needs at least 1 special character',
                'error' => '',
                'data' => null,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->first('name')) {
                return response()->json([
                    'success' => false,
                    'statuscode' => 4007,
                    'message' => $validator->errors()->first('name'),
                    'error' => $validator->errors()->first('name'),
                    'data' => null,
                ]);
            } elseif ($validator->errors()->first('email')) {
                return response()->json([
                    'success' => false,
                    'statuscode' => 500,
                    'message' => $validator->errors()->first('email'),
                    'error' => $validator->errors()->first('email'),
                    'data' => null,
                ]);
            } elseif ($validator->errors()->first('password')) {
                return response()->json([
                    'success' => false,
                    'statuscode' => 500,
                    'message' => $validator->errors()->first('password'),
                    'error' => $validator->errors()->first('password'),
                    'data' => null,
                ]);
            } elseif ($validator->errors()->first('phone')) {
                return response()->json([
                    'success' => false,
                    'statuscode' => 500,
                    'message' => $validator->errors()->first('phone'),
                    'error' => $validator->errors()->first('phone'),
                    'data' => null,
                ]);
            } elseif ($validator->errors()->first('password_confirmation')) {
                return response()->json([
                    'success' => false,
                    'statuscode' => 500,
                    'message' => $validator
                        ->errors()
                        ->first('password_confirmation'),
                    'error' => $validator
                        ->errors()
                        ->first('password_confirmation'),
                    'data' => null,
                ]);
            } elseif ($validator->errors()->first('username')) {
                return response()->json([
                    'success' => false,
                    'statuscode' => 500,
                    'message' => $validator->errors()->first('username'),
                    'error' => $validator->errors()->first('username'),
                    'data' => null,
                ]);
            }
        }

        $new_user = new User();
        $new_user->name = $request->name;
        $new_user->email = strtolower($request->email);
        $new_user->password = bcrypt($request->password);
        $new_user->save();
        //create a new token to be sent to the user.
        $user = User::select('id', 'email')
            ->where('email', $request->email)
            ->first();
        $role = Role::select('id')
            ->where('names', 'user')
            ->first();

        $userRole = new UserRole();
        $userRole->user_id = $user->id;
        $userRole->role_id = $role;
        $new_user->save();

        return response()->json([
            'success' => true,
            'statuscode' => 2002,
            'message' => 'Register Success Check Your Email Before Login',
            'error' => '',
            'data' => $new_user,
        ]);
    }

    public function registerAdmin(Request $request)
    {
        $oldPassword = $request->password;
        $newPassword = $request->password_confirmation;

        $uppercase = preg_match('@[A-Z]@', $newPassword);
        $lowercase = preg_match('@[a-z]@', $newPassword);
        $number = preg_match('@[0-9]@', $newPassword);
        $special = preg_match(
            '/^(?=(?:.*[A-Z]){1,})(?=(?:.*[a-z]){1,})(?=(?:.*\d){1,})(?=(?:.*[!@#$%^&*()\-_=+{};:,<.>]){1,})(?!.*(.)\1{2})([A-Za-z0-9!@#$%^&*()\-_=+{};:,<.>]{8,})$/',
            $newPassword
        );
        $length = strlen($newPassword) >= 8;

        if (!$uppercase) {
            return response()->json([
                'success' => false,
                'statuscode' => 400,
                'message' => 'Password needs at least 1 uppercase letter',
                'error' => '',
                'data' => null,
            ]);
        } elseif (!$lowercase) {
            return response()->json([
                'success' => false,
                'statuscode' => 400,
                'message' => 'Password needs at least 1 lowercase letter',
                'error' => '',
                'data' => null,
            ]);
        } elseif (!$number) {
            return response()->json([
                'success' => false,
                'statuscode' => 400,
                'message' => 'Password needs at least 1 number',
                'error' => '',
                'data' => null,
            ]);
        } elseif (!$length) {
            return response()->json([
                'success' => false,
                'statuscode' => 400,
                'message' => 'Password is too short (minimum is 8 characters)',
                'error' => '',
                'data' => null,
            ]);
        } elseif (!$special) {
            return response()->json([
                'success' => false,
                'statuscode' => 400,
                'message' => 'Password needs at least 1 special character',
                'error' => '',
                'data' => null,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->first('name')) {
                return response()->json([
                    'success' => false,
                    'statuscode' => 4007,
                    'message' => $validator->errors()->first('name'),
                    'error' => $validator->errors()->first('name'),
                    'data' => null,
                ]);
            } elseif ($validator->errors()->first('email')) {
                return response()->json([
                    'success' => false,
                    'statuscode' => 500,
                    'message' => $validator->errors()->first('email'),
                    'error' => $validator->errors()->first('email'),
                    'data' => null,
                ]);
            } elseif ($validator->errors()->first('password')) {
                return response()->json([
                    'success' => false,
                    'statuscode' => 500,
                    'message' => $validator->errors()->first('password'),
                    'error' => $validator->errors()->first('password'),
                    'data' => null,
                ]);
            } elseif ($validator->errors()->first('phone')) {
                return response()->json([
                    'success' => false,
                    'statuscode' => 500,
                    'message' => $validator->errors()->first('phone'),
                    'error' => $validator->errors()->first('phone'),
                    'data' => null,
                ]);
            } elseif ($validator->errors()->first('password_confirmation')) {
                return response()->json([
                    'success' => false,
                    'statuscode' => 500,
                    'message' => $validator
                        ->errors()
                        ->first('password_confirmation'),
                    'error' => $validator
                        ->errors()
                        ->first('password_confirmation'),
                    'data' => null,
                ]);
            } elseif ($validator->errors()->first('username')) {
                return response()->json([
                    'success' => false,
                    'statuscode' => 500,
                    'message' => $validator->errors()->first('username'),
                    'error' => $validator->errors()->first('username'),
                    'data' => null,
                ]);
            }
        }

        $new_user = new User();
        $new_user->name = $request->name;
        $new_user->email = strtolower($request->email);
        $new_user->password = bcrypt($request->password);
        $new_user->save();
        //create a new token to be sent to the user.
        $role = Role::select('id')
            ->where('names', $request->role)
            ->first();

        $userRole = new UserRole();
        $userRole->user_id = $new_user->id;
        $userRole->role_id = $role->id;
        $userRole->save();

        return response()->json([
            'success' => true,
            'statuscode' => 2002,
            'message' => 'Register Success',
            'error' => '',
            'data' => $new_user,
        ]);
    }
}
