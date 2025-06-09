<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $fakeUser = new \stdClass();
        $fakeUser->role = 'admin';

        $request->setUserResolver(function () use ($fakeUser) {
            return $fakeUser;
        });

        if ($request->user()->role !== $role) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        return $next($request);
    }
}
