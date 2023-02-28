<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PeterPetrus\Auth\PassportToken;

class RemoteAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected $hashids;
    protected $decodedToken;
    protected $decodedUserId;

    public function __construct($decodedToken = null, $decodedUserId = null)
    {
        $this->hashids = new Hashids('', 9);
        $this->decodedToken = $decodedToken;
        $this->decodedUserId = $decodedUserId;
    }
    public function handle($request, Closure $next)
    {
        $token = $request->header('token');
        $userId = $request->header('userId');

        $this->decodedToken = PassportToken::dirtyDecode($token);
        $this->decodedUserId = $this->hashids->decode($userId);

        if ($this->isNotLogin($token, $userId)) {
            return $this->_errorResponse(null, 'unathorized', 401);
        }

        if ($this->isExpired()) {
            return $this->_errorResponse(null, 'token expired', 401);
        }

        if (!$this->isValidToken()) {
            return $this->_errorResponse(null, 'token not valid', 401);
        }

        if ($this->isRevokedToken()) {
            return $this->_errorResponse(null, 'token revoked', 401);
        }

        // set decoded to param and request
        $request->merge([
            'decodedUserId' => $this->decodedUserId[0],
        ]);

        return $next($request);
    }
    protected function isRevokedToken()
    {
        $check = DB::table(env('DB_DATABASE') . '.oauth_access_tokens')
            ->where('id', $this->decodedToken['token_id'])
            ->first();
        if ($check == null || $check->revoked == true) {
            return true;
        } else {
            return false;
        }
    }
    protected function isNotLogin($token, $userId)
    {
        if (!isset($token) || !isset($userId)) {
            return true;
        }
    }
    protected function isExpired()
    {
        if (
            Carbon::now()->timestamp >
            Carbon::parse($this->decodedToken['expires_at'])->timestamp
        ) {
            return true;
        }
    }
    protected function isValidToken()
    {
        if ($this->decodedToken['user_id'] == $this->decodedUserId[0]) {
            return true;
        }
    }

    public function _successResponse($result = null, $message = null)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, 200);
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
