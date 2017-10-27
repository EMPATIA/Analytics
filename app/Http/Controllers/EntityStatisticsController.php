<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EntityStatisticsController extends Controller
{

    public function entityUsersRegistered(Request $request)
    {

        try {
            $entityKey = $request->header('X-ENTITY-KEY');
            if(empty($entityKey)){
                throw new Exception();
            }
            $daysInMonth = Carbon::now()->daysInMonth;
            $usersRegistered['days'] = array_fill(1,$daysInMonth,0);
            $usersRegistered['total'] = 0;
            $lastDay = new Carbon('last day of last month');

            //request to orchestrator with last day of last month


            return response()->json(["data" => $usersRegistered], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }

    }
}
