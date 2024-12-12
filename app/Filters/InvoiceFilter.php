<?php 

namespace App\Filters;


use Illuminate\Http\Request;

class InvoiceFilter extends Filter
{
    
    protected array $allowedOperatorsFields=[
        'value' =>['gt' , 'eq' , 'lt', 'gte', 'lte', 'ne'],
        'type' => ['eq', 'ne', 'in'],
        'paid' => ['eq', 'ne'],
        'payment_date' => ['gt', 'ep', 'lt', 'gte', 'lte', 'ne' ],
    ];




}