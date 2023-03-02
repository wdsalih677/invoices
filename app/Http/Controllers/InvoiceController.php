<?php

namespace App\Http\Controllers;

use App\Models\invoice;
use App\Models\Invoice_attachment;
use App\Models\invoice_detail;
use App\Models\section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = invoice::get();
        return view('invoices.invoice',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = section::get();
        return view('invoices.add',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // get request of invoices

        invoice::create([
            'invoice_number'    =>$request->invoice_number,
            'invoice_Date'      =>$request->invoice_Date,
            'Due_date'          =>$request->Due_date,
            'product'           =>$request->product,
            'section_id'        =>$request->Section,
            'Amount_collection' =>$request->Amount_collection,
            'Amount_Commission' =>$request->Amount_Commission,
            'Discount'          =>$request->Discount,
            'Rate_VAT'          =>$request->Rate_VAT,
            'Value_VAT'         =>$request->Value_VAT,
            'Total'             =>$request->Total,
            'note'              =>$request->note,
            'Status'            =>'غير مدفوعه',
            'Value_Status'      =>2,
        ]);

        // get request of invoices details

        $invoice_id = invoice::latest()->first()->id;
        invoice_detail::create([
            'invoice_id' => $invoice_id,
            'invoice_num' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name)
        ]);

        // get request of invoices attachments

        if($request->hasFile('pic')){
            $invoice_id = invoice::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number =$request->invoice_number;

            $attachments = new Invoice_attachment();
            $attachments->file_name = $file_name;
            $attachments->invoice_num = $invoice_number;
            $attachments->created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            //save image in server
            $image_name = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/'.$invoice_number),$image_name);
        }
        session()->flash('success','تم إضافة الفاتوره بنجاح ');
        return redirect('invoices');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = invoice::where('id',$id)->first();
        $sections = section::get();
        return view('invoices.edit',compact('sections','invoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoice $invoice)
    {
        $invoices = invoice::findOrFail($request->id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);

        session()->flash('Add','تم تعديل الفاتوره بنجاح ');
        return redirect('invoices');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoice = invoice::where('id',$id)->first();
        $details = Invoice_attachment::where('id',$id)->first();

        if(!empty($details->invoice_num)){
            Storage::disk('public_uploads')->deleteDirectory($details->invoice_num);
        }
        $invoice->forceDelete();
        session()->flash('delete_invoice');
        return redirect('/invoices');
    }

    /**
     * ==================================================================
     * ================function to get products==========================
     * ==================================================================
     */
    public function getproducts($id){

        $products = DB::table("products")->where("section_id",$id)->pluck("product_name","id");
        return json_encode($products);
    }
    /**
     * ==================================================================
     * ================function to show status===========================
     * ==================================================================
     */
    public function show_status($id){
        $invoices = invoice::findOrFail($id);
        return view('invoices.status_update',compact('invoices'));
    }
    /**
     * ==================================================================
     * ================function to update status=========================
     * ==================================================================
     */
    public function update_status($id ,Request $request){
        $invoices = invoice::findOrFail($id);
        if($invoices->Status == 'غير مدفوعه'){
            $invoices->update([
                'Value_Status'=>1,
                'Status'=>$request->Status,
                'Payment_Date'=>$request->Payment_Date,
            ]);

            invoice_detail::create([
                'invoice_id' => $request->invoice_id,
                'invoice_num' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'status' => $request->Status,
                'value_status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }else{
            $invoices->update([
                'Value_Status'=>3,
                'Status'=>$request->Status,
                'Payment_Date'=>$request->Payment_Date,
            ]);

            invoice_detail::create([
                'invoice_id' => $request->invoice_id,
                'invoice_num' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'status' => $request->Status,
                'value_status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');
    }
    /**
     * =====================================================================
     * ================function to get invoicesPaid=========================
     * =====================================================================
     */
    public function invoicesPaid(){
        $invoicesPaides = invoice::where('Value_Status',1)->get();
        return view('invoices.invoicesPaides',compact('invoicesPaides'));
    }
    /**
     * =======================================================================
     * ================function to get invoicesUnpaid=========================
     * =======================================================================
     */
    public function invoicesUnpaid(){
        $invoicesUnpaides = invoice::where('Value_Status',2)->get();
        return view('invoices.invoicesUnpaides',compact('invoicesUnpaides'));
    }
    /**
     * ==============================================================================
     * ================function to get invoicesPartiallyPaid=========================
     * ==============================================================================
     */
    public function invoicesPartiallyPaid(){
        $invoicesPartiallyPaides = invoice::where('Value_Status',3)->get();
        return view('invoices.invoicesPartiallyPaides',compact('invoicesPartiallyPaides'));
    }
}
