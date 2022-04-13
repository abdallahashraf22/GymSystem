<?php

namespace App\Http\Middleware;

use App\Http\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class CheckBranchManager
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
        if ($payload['role'] != 'admin' && $payload['role'] != 'city manager' && $payload['role'] != 'branch manager')
            return $this->createResponse(403, [], false, "not authorized");

        if ($payload['role'] == 'city manager')
            $request['city_id'] = $payload['city_id'];
        if ($payload['role'] == 'branch manager')
            $request['branch_id'] = $payload['branch_id'];

        if (!$request['city_id'])
            $request['city_id'] = "all";
        if (!$request['branch_id'])
            $request['branch_id'] = "all";

        return $next($request);
    }
}
