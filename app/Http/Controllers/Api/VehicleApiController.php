<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VehicleService;
use Illuminate\Http\Request;

class VehicleApiController extends Controller
{
    protected VehicleService $vehicleService;

    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $vehicles = $this->vehicleService->getAll($search);

        return response()->json($vehicles);
    }

    public function showByPlate(string $plate)
    {

    }

}
