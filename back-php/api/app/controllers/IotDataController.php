<?php

namespace app\controllers;

use app\middlewares\MiddlewareUser;
use app\models\IotData;
use app\types\Rol;
use Exception;

class IotDataController extends Controller
{
    /**
     * Método index
     *
     * Este método devuelve los datos de los dispositivos IoT.
     * Si el usuario es un administrador, devuelve todos los datos.
     * Si el usuario es un usuario normal, solo devuelve sus propios datos.
     */
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

    /**
     * Método store
     *
     * Este método crea un nuevo dato de un dispositivo IoT.
     */
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

    /**
     * Método show
     *
     * Este método devuelve un dato de un dispositivo IoT específico.
     */
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

    /**
     * Método update
     *
     * Este método no está permitido y devuelve un error 405. (referenciado por $id)
     * No tiene sentido permitir a un IoT hacer correciones espacio temporales!
     */
    public function update($id): void
    {
        response()->json(["message" => "Method not allowed"], 405);
    }

    /**
     * Método destroy
     *
     * Este método elimina un dato de un dispositivo IoT específico (referenciado por $id)
     */
    public function destroy($id): void
    {
        try {
            $auth = new MiddlewareUser(Rol::USER);
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
