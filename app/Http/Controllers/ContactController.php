<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Contact;
use App\Models\DynamicRules;

class ContactController extends Controller
{

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $contacts = $this->contact->with('addresses')->with('phones');

            if ($request->has('filters')) {
                $filters = explode('&', $request->filters);
                foreach($filters as $condition) {
                    $filter = explode(':', $condition);
                    $contacts->where($filter[0], $filter[1], $filter[2]);
                }   
            } 

            $contacts = $contacts->get();
            
            return response()->json($contacts, 200);
        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate($this->contact->rules());

        $img = $request->file('photo');
        $imgUrn = $img->store('images/profiles', 'public');

        try {
            $contact = $this->contact->create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'photo' => $imgUrn
                ]
            );

            return response()->json($contact, 201);
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
    public function show(Int $id)
    {
        try {
            $contact = $this->contact->with('addresses')->findOrFail($id);

            return response()->json($contact);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Contact not found!'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Int $id)
    {
        try {
            $contact = $this->contact->findOrFail($id);

            if ($request->method() == "PATCH") {
                $request->validate(DynamicRules::validateRules($this->contact->rules(), $request->all()));
            } else {
                $request->validate($contact->rules());
            }

            if ($request->file('photo')) {
                Storage::disk('public')->delete($contact->photo);
            }

            $contact->fill($request->all());

            if ($request->photo) {
                $img = $request->file('photo');
                $imgUrn = $img->store('images/profiles', 'public');
                $contact->photo = $imgUrn;
            }

            $contact->save();

            return response()->json($contact);
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
    public function destroy(Int $id)
    {
        try {
            $contact = $this->contact->findOrFail($id);

            Storage::disk('public')->delete($contact->photo);
            $contact->delete();

            return response()->json(['success' => 'Contact deleted!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Contact not found!'], 404);
        }
    }
}
