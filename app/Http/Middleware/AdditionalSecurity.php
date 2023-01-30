<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdditionalSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url = $request->route()->getName();
        $url = explode('.', $url);
         
        if($url[1] == "edit" || $url[1] == "update" || $url[1] == 'destroy'){
            $uuid = $request->route()->parameters["user"];
            // $user = DB::table('users')->where("id", "=", Auth::user()->id)->first();
            $data = DB::table($url[0])->where("uuid", "=", $uuid)->first();
            $user = DB::table("users")->where("id", "=", $data->id)->first();
            if($user->parent_id === Auth::user()->parent_id){
                return $next($request);
            }

            return redirect('home');

            // $data = DB::table($url[0])->where("uuid", "=", $uuid)->first();
            // if(!empty($data)){

            // }
            // dd($user->parent_id);
            // $search = User::where("uuid", "=", $uuid)->first();
            // $data = DB::table('users');
        }
        // if (Auth::user() &&  Auth::user()->is_admin == 1) {
        //      return $next($request);
        // }

        // return redirect('home')->with('error','You have not admin access');
        // dd($url[0]);
    }
}