<?php

namespace App\Http\Middleware;

use App\Models\Page;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class RegisterVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        Log::info('Request details', [
            'route' => $request->getRequestUri(),
            'method' => $request->method(),
            'userAgent' => $request->header('User-Agent'),
            'headers' => $request->headers->all(),
        ]);

        $route = $request->getRequestUri();

        if (preg_match('/\.(js|css|png|jpg|gif|ico)$/', $route)) {
            return $next($request);
        }

        if ($route != "/") {
            $route = rtrim($route, "/");
        }

        $page = Page::firstWhere(['route' => $route]);

        if ($page) {
            $page->increment('visits');

            View::share('currentPage', $page);
        }
        return $next($request);
    }
}
