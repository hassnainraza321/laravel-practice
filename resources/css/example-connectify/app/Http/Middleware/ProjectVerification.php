<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;

class ProjectVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    { 
        $ref_id = session('ref_id');
        
        if (empty($ref_id)) 
        {
            return redirect()->route('project');
        }

        $project = DB::table('projects')->where('user_id', auth()->id())->whereRaw('LOWER(reference_id) = ?', [strtolower($ref_id)])->where('is_active', 1)->first();

        if (empty($project)) 
        {
            return redirect()->route('project');
        }

        return $next($request);
       
    }
}
