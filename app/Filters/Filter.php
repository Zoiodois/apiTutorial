<?php 
namespace App\Filters;


use Illuminate\Http\Request;
use Exception;

use DeepCopy\Exception\PropertyException;




abstract class Filter 
{
    protected array $allowedOperatorsFields=[];

    protected array $translateOperatorFields = [

        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
        'eq' => '=',
        'ne' => '!=',
        'in' => 'in',
    ];


    public function filter(Request $request){
        $where = [];
        $whereIn = [];

        if (empty($this->allowedOperatorsFields)) {
            throw new PropertyException("Property allowedOperatorsFields is empty");
            //return ("Property allowedOperatorsfields is empty");
          }
      

        foreach ($this -> allowedOperatorsFields as $param => $operators){

            $queryOperator = $request->query($param);
            // $queryOperator = $request->query('paid');
            // dd($queryOperator);

            if($queryOperator){
                foreach ($queryOperator as $operator => $value){

                    if(!in_array($operator, $operators)){
                        throw new Exception("{$param} does not have {$operator} operator");
                    }

                    if(str_contains($value, '[')){
                        $whereIn[] = [
                            $param, //value
                            explode(',',str_replace(['[',']'], ['',''], $value)), //gt
                            // $value //dosent need
                        ];

                    } else {
                        $where[] = [
                            $param,
                            $this->translateOperatorFields[$operator],
                            $value
                        ];
                    }

                }
            }
        }

        if(empty($where) && empty($whereIn)){
            return [];
        }

        return [
            'where' => $where,
            'whereIn' => $whereIn
        ];
        

    }
}