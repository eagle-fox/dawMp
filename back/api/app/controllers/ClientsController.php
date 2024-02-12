<?php

namespace app\controllers;

use app\models\Client;
use app\types\IPv4;
use Exception;
use Leaf\Http\Request;

class ClientsController extends Controller
{

    public function index(): void
    {
        $clients = Client::query()->get();
        response()->json($clients);
    }

    public function store(): void
    {
        try {
            $client = new Client();
            $client->ipv4 = new IPv4(app()->request()->getIp());
            $client->token = new UUID(app()->request()->get("token"));
            $client->user = app()->request()->get("user");
        } catch (Exception $e) {
            $msg = "Error al crear el cliente.";
            if (getenv("LEAF_DEV_TOOLS")) {
                $msg .= " " . $e->getMessage();
            }
            response()->json(["message" => $msg], 500);
        }
    }

    public function show($query): void
    {
        if (filter_var($query, FILTER_VALIDATE_IP)) {
            $client = Client::query()->where("ipv4", ip2long($query))->first();
            if ($client instanceof Client) {
                response()->json($client);
            } else {
                response()->json(["message" => "Client not found"], 404);
            }
        } elseif (filter_var($query, FILTER_VALIDATE_INT)) {
            $client = Client::query()->find($query);
            if ($client instanceof Client) {
                response()->json($client);
            } else {
                response()->json(["message" => "Client not found"], 404);
            }
        } else {
            response()->json(["message" => "Invalid query parameter. Please use an IP or an ID"], 400);
        }
    }


    public function update($id): void
    {
        $client = Client::query()->find($id);
        $client->ipv4 = $request->input("ipv4");
        $client->token = $request->input("token");
        $client->client = $request->input("client");
        $client->save();
        response()->json($client);
    }

    public function delete($id): void
    {
        $client = Client::query()->find($id);
        $client->delete();
        response()->json(["message" => "Client deleted"]);
    }
}
