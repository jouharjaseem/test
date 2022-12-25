<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Invoice as In;
use App\Models\Invoicesub;
class Invoice extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invo_object = new In();
        $invo_sub = new Invoicesub();
        $result = $invo_object->select("id","name","date","filename")->get()->toArray();
        $data['result']=\json_encode($result);
        return view("invoice",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("invoiceadd");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'date' => 'required',
            'qty' => 'required',
            'tax' => 'required',
            'amount' => 'required',
           
            'file' => 'required|mimes:jpg,png,pdf,jpwg|max:3048',

        ]);
        if ($validator->fails()) {
            
            return redirect('/invoice/create')->withErrors($validator)->withInput();
        }else{
            $extension = $request->file('file')->extension();
            $fileName = rand(1000, 9999) . '-' . rand(9999, 1000) . '.' . $extension;
            $request->file('file')->move('upload/user', $fileName);
            $invo_object = new In();
            $amount = $request->input('amount');
            $qty = $request->input('qty');
            $tax = $request->input('tax');
            $data_val = [
                "name"=>$request->input('name'),
                "date"=>$request->input('date'),
                "filename"=>$fileName,
                'created_at'=>date("Y-m-d")
            ];
            $id = $invo_object->insert_data("invoice",$data_val);
            if(!empty($amount))
            {
                foreach ($amount as $key => $value) {
                    $data_val = [
                        "amount"=>$value,
                        "tax_amount"=>$tax[$key],
                        "qty"=>$qty[$key],
                        "tot_amount"=> $tot=\floatval($value) * \floatval($qty[$key]) ,
                        "net_amount"=>$tot + \floatval($tax[$key]) ,
                        "master_id"=>$id,
                        'created_at'=>date("Y-m-d")
                    ];
                    $invo_object->insert_data("invoice_sub",$data_val);
                }
            }
           
            $request->session()->flash('success', 'Saved successfully');
            return redirect('/invoice/create');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invo_object = new In();
        $invo_sub = new Invoicesub();
        $data['result'] = $invo_object->select("name","date","filename")->where("id",$id)->get()->toArray();
        $sub = $invo_sub->select("amount","tax_amount","qty")->where("master_id",$id)->get()->toArray();
        $data['amount']= \json_encode(array_column($sub,"amount"));
        $data['tax']= \json_encode(array_column($sub,"tax_amount"));
        $data['qty']=$qty= \json_encode(array_column($sub,"qty"));
        $data['cnt']=\json_encode( array_keys(array_column($sub,"qty")));
        $data['id']=$id;
       
      
        
        return view("invoiceedit",$data);
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
        
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'date' => 'required',
            'qty' => 'required',
            'tax' => 'required',
            'amount' => 'required',
           
           

        ]);
        if ($validator->fails()) {
            
            return redirect('invoice/edit/'.$id)->withErrors($validator)->withInput();
        }else{
              $invo_object = new In();
            $amount = $request->input('amount');
            $qty = $request->input('qty');
            $tax = $request->input('tax');
            $data_val = [
                "name"=>$request->input('name'),
                "date"=>$request->input('date'),
              
               
            ];
             $invo_object->update_data("invoice",$id,$data_val);
            if(!empty($amount))
            {
                $invo_object->delete_data("invoice_sub",$id);
                foreach ($amount as $key => $value) {
                    $data_val = [
                        "amount"=>$value,
                        "tax_amount"=>$tax[$key],
                        "qty"=>$qty[$key],
                        "tot_amount"=> $tot=\floatval($value) * \floatval($qty[$key]) ,
                        "net_amount"=>$tot + \floatval($tax[$key]) ,
                        "master_id"=>$id,
                        'created_at'=>date("Y-m-d")
                    ];
                    $invo_object->insert_data("invoice_sub",$data_val);
                }
            }
           
            $request->session()->flash('success', 'Saved successfully');
            return redirect('invoice/edit/'.$id);
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
        $invo_object = new In();
        $invo_object->delete_data("invoice_sub",$id);
        $invo_object->delete_data_main("invoice",$id);
    }
}
