<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

use App\Models\DynamicRules;
use App\Models\Viacep;
use App\Repositories\AddressRepository;

class AddressController extends Controller
{

    public function __construct(Address $address)
    {
        $this->address = $address;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $addressRepository = new AddressRepository($this->address);

        if ($request->has('params_contact')) {
            $paramsContact = "contact:id,{$request->params_contact}";
            $addressRepository->getRelatedAttributes($paramsContact);
        }

        if ($request->has('filters')) {
            $addressRepository->filter($request->filters);
        }

        if ($request->has('params')) {
            $addressRepository->selectAttributes($request->params);
        }

        return response()->json($addressRepository->getResult(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->address->rules());

        try {
            $address = $this->address->create($request->all());
            return response()->json($address, 201);
        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $address = $this->address->with('contact')->findOrFail($id);

            return response()->json($address);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Address not found!'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $address = $this->address->findOrFail($id);

            if ($request->method() == "PATCH") {
                $request->validate(DynamicRules::validateRules($this->address->rules(), $request->all()));
            } else {
                $request->validate($address->rules());
            }

            $address->update($request->all());

            return response()->json($address);
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
    public function destroy($id)
    {
        try {
            $address = $this->address->findOrFail($id);

            $address->delete();

            return response()->json(['success' => 'Address deleted!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Address not found!'], 404);
        }
    }

    public function getAddressByCep(Request $request){
        $request->validate([
            'cep' => 'required|min:8|max:8|regex:/^[0-9]+$/'
        ]);

        $cep = $request->cep;
        
        $address = Viacep::getAddress($cep);

        return $address;
    }
}
