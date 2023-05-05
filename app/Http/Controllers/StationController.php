<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class StationController extends Controller
{
    /**
     * Get Stations
     * @OA\Get (
     *     path="/api/stations",
     *     tags={"Stations"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="example name"
     *                     ),
     *      *              @OA\Property(
     *                         property="latitude",
     *                         type="number",
     *                         example="54.67"
     *                     ),
     *      *              @OA\Property(
     *                         property="longitude",
     *                         type="number",
     *                         example="43.43"
     *                     ),
     *      *              @OA\Property(
     *                         property="company_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *      *              @OA\Property(
     *                         property="address",
     *                         type="string",
     *                         example="example address"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {


        if ($request->query('distance')) {
            $lat = '47.66';
            $lon = '23.57';
            
            $distance = $request->query('distance');
                
            $data = DB::table("stations")
                ->select("stations.id"
                    ,DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
                    * cos(radians(stations.latitude)) 
                    * cos(radians(stations.longitude) - radians(" . $lon . ")) 
                    + sin(radians(" .$lat. ")) 
                    * sin(radians(stations.latitude))) AS distance"))
                    ->having("distance", "<=", $distance)
                    ->groupBy("stations.id")
                    ->get();

            return response()->json($data, 200);
            
        }

        $stations = Station::latest()
        ->select('id', 'name', 'latitude', 'longitude', 'company_id', 'address')
        ->paginate(5);

        return response()->json($stations, 200);
    }

    /**
     * Create Station
     * @OA\Post (
     *     path="/api/stations",
     *     tags={"Stations"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="example name"
     *                     ),
     *      *              @OA\Property(
     *                         property="latitude",
     *                         type="number",
     *                         example="54.67"
     *                     ),
     *      *              @OA\Property(
     *                         property="longitude",
     *                         type="number",
     *                         example="43.43"
     *                     ),
     *      *              @OA\Property(
     *                         property="company_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *      *              @OA\Property(
     *                         property="address",
     *                         type="string",
     *                         example="example address"
     *                     ),
     *                 ),
     *                 example={
     *                     "name":"example name",
     *                     "latitude":"54.34",
     *                     "longitude":"76.98",
     *                     "company_id":"1",
     *                     "address":"example address"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="title"),
     *              @OA\Property(property="latitude", type="number", example="name"),
     *              @OA\Property(property="longitude", type="number", example="longitude"),
     *              @OA\Property(property="company_id", type="number", example="1"),
     *              @OA\Property(property="address", type="string", example="address"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="fail"),
     *          )
     *      )
     * )
     */
    public function store(Request $request)
    {
        //Validate input

        $request->validate(
            [
                'name',
                'latitude',
                'longitude',
                'company_id',
                'address'
            ]
        );

        try{
            //Create Statiom
            Station::create($request->all());

            return response()->json(['message' => 'Station successfully created'], 201);
        } catch (Exception $error){
            return response()->json(['message' => 'Company does not exist'], 403);
        }

    }

    /**
     * Get Station
     * @OA\Get (
     *     path="/api/stations/{id}",
     *     tags={"Stations"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="1"),
     *              @OA\Property(property="latitude", type="number", example="34.54"),
     *              @OA\Property(property="longitude", type="number", example="14.65"),
     *              @OA\Property(property="company_id", type="number", example="1"),
     *              @OA\Property(property="address", type="string", example="address"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *         )
     *     )
     * )
     */
    public function show(Station $station)
    {
        try{
            return response()->json(compact('station'), 200);
        } catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Http\Response
     */
    public function edit(Station $station)
    {

    }

/**
     * Update Station
     * @OA\Put (
     *     path="/api/stations/{id}",
     *     tags={"Stations"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="example name"
     *                     ),
     *      *              @OA\Property(
     *                         property="latitude",
     *                         type="number",
     *                         example="54.67"
     *                     ),
     *      *              @OA\Property(
     *                         property="longitude",
     *                         type="number",
     *                         example="43.43"
     *                     ),
     *      *              @OA\Property(
     *                         property="company_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *      *              @OA\Property(
     *                         property="address",
     *                         type="string",
     *                         example="example address"
     *                     ),
     *                 ),
     *                 example={
     *                     "name":"example name",
     *                     "latitude":"54.34",
     *                     "longitude":"76.98",
     *                     "company_id":"1",
     *                     "address":"example address"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="1"),
     *              @OA\Property(property="latitude", type="number", example="34.54"),
     *              @OA\Property(property="longitude", type="number", example="14.65"),
     *              @OA\Property(property="company_id", type="number", example="1"),
     *              @OA\Property(property="address", type="string", example="address"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *          )
     *      )
     * )
     */
    public function update(Request $request, Station $station)
    {
        //Validate input

        $request->validate(
            [
                'name',
                'latitude',
                'longitude',
                'company_id',
                'address'
            ]
        );

        try{
            //Create Statiom
            $station->update($request->all());

            return response()->json(['message' => 'Station successfully updated'], 201);
        } catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }

    /**
     * Delete Station
     * @OA\Delete (
     *     path="/api/stations/{id}",
     *     tags={"Stations"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="delete station success")
     *         )
     *     )
     * )
     */
    public function destroy(Station $station)
    {
        try {
            $station->delete();

            return response()->json(['message' => 'Station successfully deleted'], 204);
        }catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }
}
