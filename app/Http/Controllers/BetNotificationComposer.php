<?php

namespace App\Http\View\Composers;

use App\Models\Bet;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class BetNotificationComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $availableBetsCount = 0;
        if (Auth::check()) {
            $availableBetsCount = Bet::where('status', 'pending')
                ->where('user_id', '!=', Auth::id())
                ->count();
        }
        $view->with('availableBetsCount', $availableBetsCount);
    }
}