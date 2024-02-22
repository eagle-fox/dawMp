<?php

namespace app\routes;

app()->get("/iotData", "IotDataController@index");
app()->post("/iotData", "IotDataController@store");
app()->get("/iotData/{id}", "IotDataController@show");
app()->put("/iotData/{id}", "IotDataController@update");
app()->patch("/iotData/{id}", "IotDataController@update");
app()->post("/iotData/{id}", "IotDataController@update");
app()->delete("/iotData/{id}", "IotDataController@destroy");
