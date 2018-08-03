<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return array("response" => "error", "remark" => "access denined");
});

Route::post('addaccess', function(Request $request) {
    $request = $request->json()->all();

    if(empty($request["master_key"]) || empty($request["access"])) {
        return array("response" => "error", "remark" => "missing some/all payload");
    }

    if($request["master_key"]!=env('MASTER_KEY', 'riffydaddyallhome')) {
        return array("response" => "error", "remark" => "access denined");
    }

    $data = $request["data"];
    $access_key=str_random(32);

    $access = new App\ACCESS();
    $access->key = $access_key;
    $access->division = $data["division"];
    $access->is_allowed = 1;
    $access->save();

    return array("response" => "success");
});

Route::post('getaccess', function(Request $request) {
    $request = $request->json()->all();

    if(empty($request["access_key"]) || empty($request["access"])) {
        return array("response" => "error", "remark" => "missing some/all payload");
    }

    if(!(App\ACCESS::where("key", $request["access_key"])->exists())) {
        return array("response" => "error", "remark" => "access denined");
    }

    $data=App\ACCESS::select('division','is_allowed')->where("key", $request["access_key"])->first();

    return array("response" => "success", "data" => $data);
});

Route::post('getevent', function(Request $request) {
    $request = $request->json()->all();

    if(empty($request["access_key"]) || empty($request["access"])) {
        return array("response" => "error", "remark" => "missing some/all payload");
    }

    if(!(App\ACCESS::where("key", $request["access_key"])->exists())) {
        return array("response" => "error", "remark" => "access denined");
    }

    $data = App\ACCESS::select("event_id", "event_name", "booktime_start", "booktime_end", "airport_departure", "airport_arrival")->where("token", $request["access_key"])->get();

    foreach($data as $dat) {
        $event[] = array(
            "event" => array(
                "id" => $dat["event_id"],
                "name" => $dat["event_name"]
            ),
            "booking_time" => array(
                "start" => $dat["booktime_start"],
                "end" => $dat["booktime_end"]
            ),
            "airport" => array(
                "departure" => $dat["airport_departure"],
                "arrival" => $dat["airport_arrival"]
            )
        );
    }

    return array(
        "response" => "success",
        "data" => $event
    );
});

Route::post('getflight', function(Request $request) {

    $request = $request->json()->all();

    if(empty($request["access_key"]) || empty($request["access"]) || empty($request["data"]["event"]["id"])) {
        return array("response" => "error", "remark" => "missing some/all payload");
    }

    if(!(App\ACCESS::where("key", $request["access_key"])->exists())) {
        return array("response" => "error", "remark" => "access denined");
    }

    $data = $request["data"];

    if(!(App\EVENT::where("token", $request["access_key"])->where("event_id", $data["event"]["id"])->exists())) {
        return array("response" => "error", "remark" => "event not found");
    }
    
    $query = App\EVENT::where("token", $request["access_key"])->where("event_id", $data["event"]["id"])->get();

    foreach($query as $dat) {
        $flight[] = array(
            "aircraft" => array(
                "callsign" => $dat["aircraft_callsign"],
                "model" => $dat["aircraft_model"]
            ),
            "flight" => array(
                "rule" => $dat["flight_rule"],
                "type" => $dat["flight_type"],
                "load" => $dat["flight_load"]
            ),
            "user" => array(
                "division" => $dat["user_division"],
                "vid" => $dat["user_vid"],
                "rating" => $dat["user_rating"]
            ),
            "time" => array(
                "departure" => $dat["time_departure"],
                "arrival" => $dat["time_arrival"]
            )
        );
    }
    
    return array(
        "response" => "success",
        "data" => $fight
    );
});

Route::post('createevent', function(Request $request) {
    $request = $request->json()->all();

    if(empty($request["access_key"]) || empty($request["access"]) || empty($request["data"]["event"]["name"]) || empty($request["data"]["booking_time"]["start"]) || empty($request["data"]["booking_time"]["end"]) || empty($request["data"]["airport"]["departure"]) || empty($request["data"]["airport"]["arrival"])) {
        return array("response" => "error", "remark" => "missing some/all payload");
    }

    if(!(App\ACCESS::where("key", $request["access_key"])->exists())) {
        return array("response" => "error", "remark" => "access denined");
    }

    $data = $request["data"];
    $event_id=str_random(64);

    $event = new App\EVENTS();
    $event->token = $request["access_key"];
    $event->event_id = $event_id;
    $event->event_name = $data["event"]["name"];
    $event->booktime_start = $data["booking_time"]["start"];
    $event->booktime_end = $data["booking_time"]["end"];
    $event->airport_departure = $data["airport"]["departure"];
    $event->airport_arrival = $data["airport"]["arrival"];
    $event->save();
    
    return array(
        "response" => "success",
        "data" => array(
            "event" => array(
                "id" => $event_id,
                "name" => $data["event"]["name"]
            )
        )
    );
});

Route::post('createflight', function(Request $request) {

    $request = $request->json()->all();

    if(empty($request["access_key"]) || empty($request["access"]) || empty($request["data"]["event"]["id"]) || empty($request["data"]["aircraft"]["callsign"])) {
        return array("response" => "error", "remark" => "missing some/all payload");
    }

    if(!(App\ACCESS::where("key", $request["access_key"])->exists())) {
        return array("response" => "error", "remark" => "access denined");
    }

    $data = $request["data"];

    if(!(App\EVENT::where("token", $request["access_key"])->where("event_id", $data["event"]["id"])->exists())) {
        return array("response" => "error", "remark" => "event not found");
    }

    
    $flight = new App\FLIGHT();
    $flight->event_id = $data["event"]["id"];
    $flight->aircraft_callsign = $data["aircraft"]["callsign"];
    $flight->save();
    
    return array("response" => "success");
});

Route::post('reserveflight', function(Request $request) {

    // $request = $request->json()->all();

    // if(empty($request["access_key"]) || empty($request["access"]) || empty($request["data"]["event"]["id"]) || empty($request["data"]["aircraft"]["callsign"])) {
    //     return array("response" => "error", "remark" => "missing some/all payload");
    // }

    // if(!(App\ACCESS::where("key", $request["access_key"])->exists())) {
    //     return array("response" => "error", "remark" => "access denined");
    // }

    // $data = $request["data"];

    // if(!(App\EVENT::where("token", $request["access_key"])->where("event_id", $data["event"]["id"])->exists())) {
    //     return array("response" => "error", "remark" => "event not found");
    // }

    
    // $flight = new App\FLIGHT();
    // $flight->event_id = $data["event"]["id"];
    // $flight->aircraft_callsign = $data["aircraft"]["callsign"];
    // $flight->save();
    
    // return array("response" => "success");
});

Route::fallback(function(){
    return array("response" => "error", "remark" => "access denined");
});