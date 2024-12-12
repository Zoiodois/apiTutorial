<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     private array $types=['C' => 'Cartao', 'B' => 'Boleto', 'P'=> 'Pix'];
    public function toArray(Request $request): array
    {   
        $paid = $this->paid;
        return [
            'user'=>[
                'firstname' => $this->user->firstname,
                'lastname' => $this->user->lastname,
                'fullname' => $this->user->firstname . ' ' . $this->user->lastname,
                'email' => $this->user->email,
            ],
            'type' => $this->types[$this->type],
            'value' => 'R$' . number_format($this->value, 2, ',' , '.'),
            'paid' => $paid ?'Pago' : 'Nao pago',
            'Paymentdate' => $paid ? Carbon::parse($this->payment_date)->format('d/m/Y H:i:s') : Null,
            'Paymentdince' => $paid ? Carbon::parse($this->payment_date)->diffForHumans() : Null,
        ];
    }
}
