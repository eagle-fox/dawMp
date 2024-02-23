<?php

namespace app\controllers;

use app\middlewares\MiddlewareUser;
use app\models\IotData;
use app\types\Rol;
use Exception;
use Illuminate\Support\Facades\DB;

class IotDataController extends Controller
{

    public function index(): void
    {
        try {
            $auth = new MiddlewareUser(Rol::USER);
            $user = $auth->getUser();

            if ($user->rol == Rol::ADMIN) {
                $data = DB::select("
                SELECT *
                FROM iot_data
                WHERE device IN (
                    SELECT device
                    FROM (
                        SELECT device, MAX(id) as id
                        FROM iot_data
                        GROUP BY device
                    ) as subquery
                )
            ");
            } elseif (Rol::USER) {
                $data = DB::select("
                SELECT *
                FROM iot_data
                WHERE device IN (
                    SELECT id
                    FROM iot_devices
                    WHERE user = ?
                )
                ", [$user->id]);
            }

            response()->json(["message" => "All data", "data" => $data]);

        } catch (Exception $e) {
            $msg = "Error al mostrar los datos";
            if (getenv("LEAF_DEV_TOOLS")) {
                $msg .= ": " . $e->getMessage();
            }
            response()->json(["message" => $msg], 500);
        }
    }

    public function store(): void
    {
        try {
            $auth = new MiddlewareUser(Rol::IOT);
            $user = $auth->getUser();
            $device = app()->request()->get("device");
            $latitude = app()->request()->get("latitude");
            $longitude = app()->request()->get("longitude");

            $newData = new IotData();
            $newData->device = $device;
            $newData->latitude = $latitude;
            $newData->longitude = $longitude;
            $newData->save();

            response()->json(["message" => "Data created", "data" => $newData]);

        } catch (Exception $e) {
            $message = "Error al crear el dato";
            if (getenv("LEAF_DEV_TOOLS")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }

    }

    public function show($id): void
    {
        try {
            $auth = new MiddlewareUser(Rol::USER);
            $user = $auth->getUser();
            $data = IotData::query()->find($id);
            if ($data === null) {
                response()->json(["message" => "Data not found"], 404);
            } else {
                response()->json(["message" => "Data found", "data" => $data]);
            }
        } catch (Exception $e) {
            $msg = "Error al mostrar el dato";
            if (getenv("LEAF_DEV_TOOLS")) {
                $msg .= ": " . $e->getMessage();
            }
            response()->json(["message" => $msg], 500);
        }
    }

    public function update($id): void
    {
        try {
            $auth = new MiddlewareUser(Rol::USER);
            $user = $auth->getUser();
            $data = IotData::query()->find($id);
            if ($data === null) {
                response()->json(["message" => "Data not found"], 404);
            } else {
                $data->latitude = app()->request()->get("latitude");
                $data->longitude = app()->request()->get("longitude");
                $data->save();
                response()->json(["message" => "Data updated", "data" => $data]);
            }
        } catch (Exception $e) {
            $msg = "Error al actualizar el dato";
            if (getenv("LEAF_DEV_TOOLS")) {
                $msg .= ": " . $e->getMessage();
            }
            response()->json(["message" => $msg], 500);
        }

    }

    public function destroy($id): void
    {
        try {
            $auth = new MiddlewareUser(Rol::USER);
            $user = $auth->getUser();
            $data = IotData::query()->find($id);
            if ($data === null) {
                response()->json(["message" => "Data not found"], 404);
            } else {
                $data->delete();
                response()->json(["message" => "Data deleted"]);
            }
        } catch (Exception $e) {
            $msg = "Error al eliminar el dato";
            if (getenv("LEAF_DEV_TOOLS")) {
                $msg .= ": " . $e->getMessage();
            }
            response()->json(["message" => $msg], 500);
        }
    }

}
