<?php

namespace app\routes;

app()->get("/iotData", "iotDataController@index");
app()->post("/iotData", "iotDataController@store");
app()->get("/iotData/{id}", "iotDataController@show");
app()->put("/iotData/{id}", "iotDataController@update");
app()->patch("/iotData/{id}", "iotDataController@update");
app()->post("/iotData/{id}", "iotDataController@update");
app()->delete("/iotData/{id}", "iotDataController@destroy");
