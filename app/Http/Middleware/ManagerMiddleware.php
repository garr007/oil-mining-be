<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    use ApiResponser;

    /**
     * Manager || admin only
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $division): Response
    {
        $payload = auth()->payload()->toArray();

        if ($payload['is_admin']) {
            return $next($request);
        }

        if ($payload['is_manager']) {
            if ($division == "") {
                return $next($request);
            }

            if (strcasecmp($division, $payload['division']) == 0) {
                return $next($request);
            }
        }

        return $this->response(null, "Unauthorized", Response::HTTP_UNAUTHORIZED);
    }
}
