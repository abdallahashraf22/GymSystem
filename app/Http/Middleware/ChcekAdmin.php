<?php

namespace App\Http\Middleware;

use App\Http\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class ChcekAdmin
{

    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $payload = auth()->payload();
        if ($payload['role'] != 'admin')
            return $this->createResponse(403, [], false, "not authorized");

        return $next($request);
    }
}
