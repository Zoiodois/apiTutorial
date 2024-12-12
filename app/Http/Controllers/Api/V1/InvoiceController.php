<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\V1\InvoiceResource;
use App\Traits\HttpResponses;

class InvoiceController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        // $this->middleware('auth:sanctum')->only(['store', 'update']);
        $this->middleware(['auth:sanctum','ability:invoice-store, user-update'])->only(['store', 'update']);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        // return InvoiceResource::collection(Invoice::all());
        // return InvoiceResource::collection(Invoice::where('user')->get());
        // return InvoiceResource::collection(Invoice::with('user')->get());
        // return ('yeah');
        return (new Invoice())->filter($request);
    }


        /**
         * Show the form for creating a new resource.
         */
        // public function create()
        // {
        //     //
        // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!auth()->user()->tokenCan('invoice-store')){
            return  $this->response('User not Authorized', 403 );
        }
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|max:1',
            'paid' => 'required|numeric|between:0,1',
            'payment_day' => 'nullable',
            'value' => 'required|numeric|between:1,9999.99',
        ]);
        
        if($validator->fails()){
            return  $this->error('Data Invalid', 422 , $validator->errors());
        }

        $created = Invoice::create($validator->validated());

        if($created){
            return  $this->response('Invoice created', 200, new InvoiceResource ($created->load('user')));
        }

        return  $this->error('Invoice not created', 400 );
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice) //Binded Invoice  Remove Invoice and Add "string $id"
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'type' => 'required|max:1|in:' . implode(',', ['B', 'C', 'P']),
            'paid' => 'required|numeric|between:0,1',
            'payment_date' => 'nullable|date_format:Y-m-d H:i:s',
            'value' => 'required|numeric',
        ]);

        if($validator->fails()){
            return $this->error('Validation failed', 422, $validator->errors());
        }

        $validated= $validator->validated();
        
        $updated = $invoice->update([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'paid' => $validated['paid'],
            'value' => $validated['value'],
            'payment_date' => $validated['paid']  ?  $validated['payment_date'] : null,
            ]) ;


        // $updated = Invoice::find($id)->update([
        //     'user_id' => $validated['user_id'],
        //     'type' => $validated['type'],
        //     'paid' => $validated['paid'],
        //     'value' => $validated['value'],
        //     'payment_date' => $validated['paid']  ?  $validated['payment_date'] : null,
        //     ]) ;
            
        // $invoice = Invoice::find($id); //-> pode ser Binded 


        //One possible exemple. Dosen't bind the response as a InvoiceResource
        // if($updated){    
        //     return $this->response('Invoice Updated', 200, $request->all());
        
        // }

        if($updated){
            return $this->response('Invoice Updated', 200, new InvoiceResource($invoice->load('user')));
        
        }

        return $this->error('Invoice not Updated', 400 );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $deleted = $invoice->delete();

        if($deleted){
            return $this->response('Invoice Deleted', 200);

        }

        return $this->response('Not Deleted', 400);
    }
}
