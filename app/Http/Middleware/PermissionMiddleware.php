<?php

namespace App\Http\Middleware;

use App\Models\RolePermission;
use App\Models\UserRole;
use Closure;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected $hashids;
    protected $decodedUserId;

    public function __construct($decodedUserId = null)
    {
        $this->hashids = new Hashids('', 9);
        $this->decodedUserId = $decodedUserId;
    }

    public function handle(Request $request, Closure $next)
    {
        $userId = $request->header('userId');
        $this->decodedUserId = $this->hashids->decode($userId);
        $user = UserRole::where('user_id', $this->decodedUserId)->first();
        $rolePermissions = RolePermission::with(['permission'])
            ->where('role_id', $user->role_id)
            ->get();
        $route = $request->route()->getName();
        $data = null;
        foreach ($rolePermissions as $rolePermission) {
            if ($route == $rolePermission->permission->names) {
                $data = 'get';
            }
        }

        if (isset($data)) {
            return $next($request);
        } else {
            return $this->_errorResponse(null, 'Url Not Found', 403);
        }
    }

    public function _errorResponse($error = null, $message = null, $code = 403)
    {
        $response = [
            'success' => false,
            'message' => $message,
            'error' => $error,
        ];

        return response()->json($response, $code);
    }
}
