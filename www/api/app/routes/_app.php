<?php

app()->get('/', function () {
    response()->json(['message' => 'Congrats!! You\'re on Leaf API']);
});

app()->get('/yeison', function () {
    response()->json(['message' => 'Yeison']);
});
