<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isBanned()) {
            $remainingDays = auth()->user()->getRemainingBanDays();
            $banReason = auth()->user()->ban_reason;
            
            auth()->logout();
            
            return redirect()->route('login')->with('error', 
                "သင့်အကောင့်အား ပိတ်ပင်ထားပါသည်။ ကျန်ရှိရက်: {$remainingDays} ရက်။ အကြောင်းအရင်း: {$banReason}");
        }

        return $next($request);
    }
}
