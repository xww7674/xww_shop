<?php


Route::get('/', function () {
    return view('welcome');
});

Route::post('/deploy','DeploymentController@deploy');
