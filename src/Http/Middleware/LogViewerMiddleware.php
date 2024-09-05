<?php

namespace Suhailparad\LogViewer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogViewerMiddleware{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         view()->share([
            'app_name' => config('app.name'),
            'app_url' => config('log-viewer.back_url', '#'),
            'title' => config('log-viewer.custom_title', 'Log Viewer'),
            'query' => request('query', null),
        ]);

        return $next($request);
    }

}
