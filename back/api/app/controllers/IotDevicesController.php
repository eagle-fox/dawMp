<?php

namespace app\controllers;

use app\middlewares\MiddlewareUser;
use app\models\IotDevice;
use app\types\Rol;
use app\types\UUID;
use Exception;

class IotDevicesController extends Controller
{

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
            response()->json(["message" => $msg], 500);
        }
    }

    public function store(): void
    {
        try {
            $ownership = app()->request()->get("user");
            $auth = new MiddlewareUser(Rol::USER, $ownership);
            $uuidIotDevice = new UUID(app()->request()->get("uuid"));
            $newDevice = new IotDevice();
            $newDevice->uuid = $uuidIotDevice;
            $newDevice->user = $ownership;
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
                    $device->uuid = new UUID(app()->request()->get("uuid"));
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
}
