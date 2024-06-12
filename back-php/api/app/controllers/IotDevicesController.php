<?php

namespace app\controllers;

use app\middlewares\MiddlewareUser;
use app\models\IotDevice;
use app\types\Rol;
use app\types\UUID;
use Exception;

/**
 * Clase IotDevicesController
 *
 * Esta clase se encarga de gestionar los dispositivos IoT.
 */
class IotDevicesController extends Controller
{

    /**
     * Método index
     *
     * Este método devuelve todos los dispositivos IoT, sólo es accesible por un administrador.
     */
    public function index(): void
    {
        try {
            $auth = new MiddlewareUser(Rol::ADMIN);
            $devices = IotDevice::query()->get();
            response()->json(["message" => "All devices", "devices" => $devices]);
        } catch (Exception $e) {
            $msg = "Error al mostrar los dispositivos";
            if (getenv("LEAF_DEV_TOOLS")) {
                $msg .= ": " . $e->getMessage();
            }
            $response = ["message" => $msg];
            response()->json($response, 500);
        }
    }

    /**
     * Método store
     *
     * Este método crea un nuevo dispositivo IoT a nombre del usuario autenticado.
     */
    public function store(): void
    {
        try {

            $auth = new MiddlewareUser(Rol::USER);
            $uuidIotDevice = new UUID(app()->request()->get("uuid"));
            $newDevice = new IotDevice();
            $newDevice->name = app()->request()->get("name");
            $newDevice->especie = app()->request()->get("especie");
            $newDevice->cumpleanos = new \DateTime(app()->request()->get("cumpleaños"));
            $newDevice->token = $uuidIotDevice;
            $newDevice->user = $auth->getUser()->id;
            $newDevice->name = app()->request()->get("name");
            $newDevice->save();
            response()->json(["message" => "Device created", "device" => $newDevice]);
        } catch (Exception $e) {
            $message = "Error al crear el dispositivo";
            if (getenv("LEAF_DEV_TOOLS")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }
    }

    /**
     * Método show
     *
     * Este método devuelve un dispositivo IoT específico, sólo es accesible por el propietario del dispositivo
     * o en su defecto por un administrador.
     */
    public function show($id): void
    {
        try {
            $ownership = app()->request()->get("user");
            $auth = new MiddlewareUser(Rol::USER, $ownership);
            $device = IotDevice::query()->find($id);
            if ($device === null) {
                response()->json(["message" => "Device not found"], 404);
            } else {
                response()->json(["message" => "Device found", "device" => $device]);
            }
        } catch (Exception $e) {
            $msg = "Error al mostrar el dispositivo";
            if (getenv("LEAF_DEV_TOOLS")) {
                $msg .= ": " . $e->getMessage();
            }
            response()->json(["message" => $msg], 500);
        }
    }

    /**
     * Método update
     *
     * Este método actualiza un dispositivo IoT específico, sólo es accesible por el propietario del dispositivo
     * o en su defecto por un administrador.
     */
    public function update($id): void
    {
        try {
            $ownership = app()->request()->get("user");
            $auth = new MiddlewareUser(Rol::USER, $ownership);
            $device = IotDevice::query()->find($id);
            if ($device instanceof IotDevice) {
                if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
                    // For PATCH requests, only update the attributes that are passed in the request
                    $fillable = $device->getFillable();
                    foreach ($fillable as $attribute) {
                        if (app()->request()->get($attribute) !== null) {
                            $device->$attribute = app()->request()->get($attribute);
                        }
                    }
                } else {
                    // For PUT requests, update all attributes
                    $device->token = new UUID(app()->request()->get("uuid"));
                    $device->user = app()->request()->get("user");
                }
                $device->save();
                response()->json(["message" => "Device updated", "device" => $device]);
            } else {
                response()->json(["message" => "Device not found"], 404);
            }
        } catch (Exception $e) {
            $message = "Error al actualizar el dispositivo";
            if (getenv("LEAF_DEV_TOOLS")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }
    }

    /**
     * Método destroy
     *
     * Este método elimina un dispositivo IoT específico, sólo es accesible por el propietario del dispositivo
     * o en su defecto por un administrador.
     */
    public function destroy($id): void
    {
        try {
            $ownership = app()->request()->get("user");
            $auth = new MiddlewareUser(Rol::USER, $ownership);
            $device = IotDevice::query()->find($id);
            if ($device instanceof IotDevice) {
                $device->delete();
                response()->json(["message" => "Device deleted"]);
            } else {
                response()->json(["message" => "Device not found"], 404);
            }
        } catch (Exception $e) {
            $message = "Error al eliminar el dispositivo";
            if (getenv("LEAF_DEV_TOOLS")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }
    }



}
