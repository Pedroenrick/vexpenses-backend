<?php

namespace App\Http\Controllers;

use App\Models\DynamicRules;
use App\Models\Phone;
use App\Repositories\PhoneRepository;
use Illuminate\Http\Request;

class PhoneController extends Controller
{

    public function __construct(Phone $phone)
    {
        $this->phone = $phone;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $phoneRepository = new PhoneRepository($this->phone);

        if ($request->has('params_contact')) {
            $paramsContact = "contact:id,{$request->params_contact}";
            $phoneRepository->getRelatedAttributes($paramsContact);
        }

        if ($request->has('filters')) {
            $phoneRepository->filter($request->filters);
        }

        if ($request->has('params')) {
            $phoneRepository->selectAttributes($request->params);
        }

        return response()->json($phoneRepository->getResult(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate($this->phone->rules());

        try {
            $this->phone->create($request->all());

            return response()->json([
                "message" => "Phone created successfully"
            ], 201);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            $phone = $this->phone->with('contact')->findOrFail($id);

            return response()->json($phone);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Phone not found!'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $phone = $this->phone->findOrFail($id);

            if ($request->method() == "PATCH") {
                $request->validate(DynamicRules::validateRules($this->phone->rules(), $request->all()));
            } else {
                $request->validate($phone->rules());
            }

            $phone->update($request->all());

            return response()->json($phone);
        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $phone = $this->phone->findOrFail($id);

            $phone->delete();

            return response()->json(['success' => 'Phone deleted!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Phone not found!'], 404);
        }
    }
}
