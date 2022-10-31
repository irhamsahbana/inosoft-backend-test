<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Libs\Repositories\Finder\VehicleFinder;
use App\Libs\Response;

use App\Libs\Repositories\VehicleRepository;
use App\Libs\Services\Vehicle;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $finder = new VehicleFinder();

        if ($request->has('year'))
            $finder->setYear((int) $request->year);

        if ($request->has('color'))
            $finder->setColor((string) $request->color);

        if ($request->has('price'))
            $finder->setPrice((float) $request->price);

        if ($request->has('vehicle_type'))
            $finder->setVehicleType((string) $request->vehicle_type);

        if ($request->has('engine'))
            $finder->setEngine((string) $request->engine);

        if ($request->has('suspension_type'))
            $finder->setSuspensionType((string) $request->suspension_type);

        if ($request->has('transmission_type'))
            $finder->setTransmissionType($request->transmission_type);

        if ($request->has('capacity'))
            $finder->setCapacity((int) $request->capacity);

        if ($request->has('type'))
            $finder->setType((string)$request->type);

        if ($request->has('paginate')) {
            $finder->usePagination((bool) $request->paginate);

            if ($request->has('page'))
                $finder->setPage((int) $request->page);

            if ($request->has('per_page'))
                $finder->setPerPage((int) $request->per_page);
        }

        $paginator = $finder->get();
        $data = [];

        if (!$finder->isUsePagination()) {
            foreach ($paginator as $x) {
                $data[] = $x;
            }
        } else {
            foreach ($paginator->items() as $x) {
                $data[] = $x;
            }

            foreach ($paginator->toArray() as $key => $value) {
                if ($key != 'data')
                    $data['pagination'][$key] = $value;
            }
        }

        $response = new Response;
        return $response->json($data, 'success to get data');
    }

    public function showStock($uuid)
    {
        $response = new Response;

        $repository = new VehicleRepository();
        $service = new Vehicle($repository);
        $data = $service->getVehicleStock($uuid);

        if ($data == null)
            return $response->json(null, 'data not found', 404);

        return $response->json($data, 'success to get data');
    }

    public function showSalesReport($uuid)
    {
        $response = new Response;

        $repository = new VehicleRepository();
        $service = new Vehicle($repository);
        $data = $service->getVehicleSalesReport($uuid);

        if ($data == null)
            return $response->json(null, 'data not found', 404);

        return $response->json((array) $data, 'success to get data');
    }

    public function showSalesReports()
    {
        $response = new Response;

        $repository = new VehicleRepository();
        $service = new Vehicle($repository);
        $data = $service->getSalesReports();

        if ($data == null)
            return $response->json(null, 'data not found', 404);

        return $response->json($data, 'success to get data');
    }

    public function saleVehicle($uuid, Request $request)
    {
        $response = new Response;

        $field = $request->all();
        $rules = [
            'quantity' => 'required|integer|min:1'
        ];

        $validator = Validator::make($field, $rules);

        if ($validator->fails())
            return $response->json(null, $validator->errors(), 422);

        $repository = new VehicleRepository();
        $service = new Vehicle($repository);
        $data = $service->saleVehicle($uuid, (int) $request->quantity);

        if ($data == null)
            return $response->json(null, 'data not found', 404);

        return $response->json((array) $data, 'success to sale vehicle');
    }
}
