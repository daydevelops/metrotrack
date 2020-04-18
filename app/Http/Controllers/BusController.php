<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

class BusController extends Controller
{
    public function index()
    {
        $busses = Cache::get('busses');
        return $busses ? $busses : $this->update();
    }

    protected function update() {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'https://www.metrobustransit.ca/api/timetrack_03202019.asp')->getBody();
        
        $busses = array();
        $count = substr_count($res, 'current_route');
        for ($i = 0; $i < $count; $i++) {
            // get route name
            $loc1 = strpos($res, 'current_route=') + 14;
            $loc2 = strpos($res, "&message=");
            $route = substr($res, $loc1, $loc2 - $loc1);
            // get lat long
            $loc1 = strpos($res, '&latlon=') + 8;
            $loc2 = strpos($res, "&heading=");
            $latlon = explode(',', substr($res, $loc1, $loc2 - $loc1));
        
            $busses[$route] = $latlon;
            $res = substr($res, $loc2 + 10, strlen($res) - $loc2);
        }
        Cache::put('busses',$busses,30); // store busses for 30 seconds
        return $busses;
    }

    //
}
