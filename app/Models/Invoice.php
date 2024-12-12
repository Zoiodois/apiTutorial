<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Filters\InvoiceFilter;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\V1\InvoiceResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
            'type',
            'paid',
            'payment_day',
            'value',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function filter(Request $request){
        // dd( $queryFilter);
        $queryFilter = (new InvoiceFilter)->filter($request);

       
        if(empty($queryFilter)){

            return ('esta Vazio');
            // return InvoiceResource::collection(Invoice::with('user')->get());
        }

        $data = Invoice::with('user');

        if(!empty($queryFilter['whereIn'])){

            foreach($queryFilter['whereIn'] as $value){
                $data->whereIn($value[0], $value[1]);

            }
        }

        $resource = $data->where($queryFilter['where'])->get();

        return  InvoiceResource::collection($resource);

    }
}
