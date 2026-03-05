<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsJudge
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, ['judge', 'jurado'], true)) {
            abort(403, 'Acceso restringido para jurados.');
        }

        return $next($request);
    }
}
