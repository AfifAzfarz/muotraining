<?php

use App\Http\Controllers\Integration\EndpointController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/endpoint', function (Request $request) {
    return 'Hello MUO';
    // response()->json([
    //     'success' => true,
    //     'message' => 'Hello World',
    //     'data'    => null
    // ], 200);
    // return response()->json([
    //     'success' => true,
    //     'message' => 'Request successful',
    //     'data'    => null,
    // ], Response::HTTP_OK); // 200
});

Route::get('post-list', [EndpointController::class, 'post']);
Route::get('negeri-fetch', [EndpointController::class, 'fetchAndStore']);
