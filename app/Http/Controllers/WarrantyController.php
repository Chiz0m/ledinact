<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarrantyResource;
use Illuminate\Http\Request;
use App\Warranty;
use Carbon\Carbon;

class WarrantyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): WarrantyResource
    {
        //
        $dt = Carbon::now();
        $warranty = Warranty::where('deleted_at', null)->orderBy('id', 'DESC')->paginate(100);

        return new WarrantyResource($warranty);
    }


    /**
     * Get fixed warranty
     */
    public function fixedWarranty(): WarrantyResource
    {
        //
        $warranty = Warranty::where(['deleted_at' => null, 'status' => 1])->orderBy('id', 'DESC')->paginate(400);

        return new WarrantyResource($warranty);
    }



    /**
     * Get not-fixed warranty
     */
    public function notFixedWarranty(): WarrantyResource
    {
        //
        $warranty = Warranty::where(['deleted_at' => null, 'status' => null])->orderBy('id', 'DESC')->paginate(400);

        return new WarrantyResource($warranty);
    }

    /**
     * Get void warranty
     */
    public function voidWarranty(): WarrantyResource
    {
        //
        $warranty = Warranty::where(['deleted_at' => null, 'status' => 2])->orderBy('id', 'DESC')->paginate(400);

        return new WarrantyResource($warranty);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): WarrantyResource
    {
        //
        $request->validate([
            'serial_number' => 'required',
            'name' => 'required',
            'description' => 'required',
        ]);

        $warranty = Warranty::create($request->all());

        return new WarrantyResource($warranty);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): WarrantyResource
    {
        //
        $warranty = Warranty::where('deleted_at', null)->findOrFail($id);

        return new WarrantyResource($warranty);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateState(Request $request, $id): WarrantyResource
    {

        $warranty = Warranty::where('deleted_at', null)->findOrFail($id);
        if ($request->status == "") {
            $warranty->status = null;
        } else {
            $warranty->status = $request->status;
        }

        if ($request->note == "") {
            $warranty->note = null;
        } else {
            $warranty->note = $request->note;
        }


        $warranty->save();

        return new WarrantyResource($warranty);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $warranty = Warranty::where('deleted_at', null)->findOrFail($id);
        $warranty->deleted_at = \Carbon\Carbon::now();
        $warranty->save();

        return response()->json(['data' => ['message' => 'Deleted']]);
    }
}
