<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseOrderResource;
use Illuminate\Http\Request;
use App\PurchaseOrder;
use function GuzzleHttp\Promise\all;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): PurchaseOrderResource
    {
        //get all the purchase orders
        $purchase_order = PurchaseOrder::where('deleted', null)->orderBy('id', 'DESC')->paginate(5);

        return new PurchaseOrderResource($purchase_order);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): PurchaseOrderResource
    {
        //store purchase order
        $request->validate([
            'purchase_order_number' => 'required',
            'customer_name' => 'required',
            'destination_address' => 'required',
            'phone' => 'required',
        ]);

        $purchase_order = PurchaseOrder::create($request->all());
        // the message
        $msg = "New mail from webapp \nPlease check mail";

        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg, 70);

        // send email
        mail("chizomreal@gmail.com", "Testing mail", $msg);
        return new PurchaseOrderResource([$purchase_order, 'message' => 'Purchase Order Has Been Added']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): PurchaseOrderResource
    {
        //
        $purchase_order = PurchaseOrder::where('deleted', null)->findOrFail($id);

        return new PurchaseOrderResource($purchase_order);
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
        //
        $request->validate([
            'purchase_order_number' => 'required',
            'customer_name' => 'required',
            'destination_address' => 'required',
            'phone' => 'required',
        ]);

        $purchase_order = PurchaseOrder::where(['deleted' => null])->findOrFail($id);
        $purchase_order->update($request->all());

        return new PurchaseOrderResource($purchase_order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //delete a purchase order just creates a date and adds it to the deleted column
        $purchase_order = PurchaseOrder::findOrFail($id);
        $purchase_order->deleted = \Carbon\Carbon::now();
        $purchase_order->save();

        return response()->json(['message' => 'Deleted']);
    }


    /** API to activate purchase by a user
     * 
     */
    public function activate(Request $request, $purchase_order_number)
    {
        $purchase_order = PurchaseOrder::where('purchase_order_number', $purchase_order_number)->firstOrFail();
        if ($purchase_order->activation_date == NULL) {
            $purchase_order->activation_status = auth()->user()->id;
            $purchase_order->activation_date =
                \Carbon\Carbon::now();
            $purchase_order->save();
            $message = 'Purchase Order Is Activated';
        } else {
            $message = 'Cannot be activated. Contact info@ledinaction.com';
        }


        return response()->json(['message' => $message]);
    }
}
