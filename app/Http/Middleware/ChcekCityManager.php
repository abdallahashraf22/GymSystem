<?php

namespace App\Http\Middleware;

use App\Http\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class ChcekCityManager
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * request --> middlewares -> action
     */
    public function handle(Request $request, Closure $next)
    {
        $payload = auth()->payload();
        if ($payload['role'] != 'admin' && $payload['role'] != 'city manager')
            return $this->createResponse(403, [], false, "not authorized");

        if ($payload['role'] == 'city manager')
            $request['city_id'] = $payload['city_id'];
        return $next($request);
    }
}
