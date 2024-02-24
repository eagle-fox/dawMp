<?php

namespace app\controllers;

use app\middlewares\MiddlewareUser;
use app\models\IotData;
use app\types\Rol;
use Exception;

class IotDataController extends Controller
{

    public function index(): void
    {
        try {
            $data = null;
            $auth = new MiddlewareUser(Rol::USER);
            $user = $auth->getUser();
            ini_set('memory_limit', '1G');
            set_time_limit(300);
            if ($user->rol == Rol::ADMIN) {
                $data = IotData::query()->orderBy("updated_at", "desc")->limit(1024)->get();
            } else if ($user->rol == Rol::USER) {
                $data = IotData::query()->where("user", $user->id)->limit(1024)->orderBy("updated_at", "desc")->get();
            } else {
                response()->json(["message" => "Unauthorized"], 401);
            }
            if ($data === null) {
                response()->json(["message" => "No data found"], 404);
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
