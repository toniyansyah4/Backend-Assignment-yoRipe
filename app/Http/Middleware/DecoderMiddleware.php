<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Hashids\Hashids;

class DecoderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $tableId = $request->route('tableId');
        if (isset($tableId)) {
            $hashTableId = new Hashids('', 9);
            $decodedTableId = $hashTableId->decode($tableId);
            if (isset($decodedTableId[0])) {
                $request->route()->setParameter('tableId', $decodedTableId[0]);
            } else {
                $request->route()->setParameter('tableId', $decodedTableId);
            }
        }
        return $next($request);
    }
}
