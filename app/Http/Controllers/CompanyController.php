<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

  /**
 * @OA\Info(
 *    title="Companies",
 *    version="1.0.0",
 * ),
 */  

class CompanyController extends Controller
{
    /**
     * Get Companies
     * @OA\Get (
     *     path="/api/companies",
     *     tags={"Companies"},
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
     *                         property="parent_company_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="example name"
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
    public function index()
    {

        try {
 
            $companies = Company::latest()
            ->select('id', 'parent_company_id', 'name')
            ->paginate(5);
 
            return response()->json(['status' => 200, 'data' => $companies]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Create Company
     * @OA\Post (
     *     path="/api/companies",
     *     tags={"Companies"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="parent_company_id",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "parent_company_id":"1",
     *                     "name":"example name"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="parent_company_id", type="number", example="title"),
     *              @OA\Property(property="name", type="string", example="name"),
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
                'parent_company_id',
                'name'
            ]
        );

        try{
            //Create Statiom
            Company::create($request->all());

            return response()->json(['message' => 'Company created successfully'], 201);

        } catch (Exception $error){

            return response()->json(['message' => 'Company does not exist'], 403);
        }

    }

    /**
     * Get Company
     * @OA\Get (
     *     path="/api/companies/{id}",
     *     tags={"Companies"},
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
     *              @OA\Property(property="parent_company_id", type="number", example="1"),
     *              @OA\Property(property="name", type="string", example="name"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *         )
     *     )
     * )
     */
    public function show(Company $company)
    {
        try{
            return response()->json(compact('company'), 200);
        } catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }

    /**
     * Update Company
     * @OA\Put (
     *     path="/api/companies/{id}",
     *     tags={"Companies"},
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
     *                          property="parent_company_id",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "parent_company_id":"1",
     *                     "name":"example name"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="parent_company_id", type="number", example="title"),
     *              @OA\Property(property="name", type="string", example="content"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *          )
     *      )
     * )
     */
    public function update(Request $request, Company $company)
    {
        //Validate input

        $request->validate(
            [
                'parent_company_id',
                'name',
            ]
        );

        try {
            //Create Statiom
            $company->update($request->all());

            return response()->json(['message' => 'Company successfully updated'], 201);
        }catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }

    /**
     * Delete Company
     * @OA\Delete (
     *     path="/api/companies/{id}",
     *     tags={"Companies"},
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
     *             @OA\Property(property="message", type="string", example="delete company success")
     *         )
     *     )
     * )
     */
    public function destroy(Company $company)
    {
        try {
            $company->delete();

            return response()->json(['message' => 'Company successfully deleted'], 204);
        }catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }
}
