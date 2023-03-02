<?php

namespace App\Http\Controllers;

use App\Models\invoice;
use App\Models\Invoice_attachment;
use App\Models\invoice_detail;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoice_detail  $invoice_detail
     * @return \Illuminate\Http\Response
     */
    public function show(invoice_detail $invoice_detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoice_detail  $invoice_detail
     * @return \Illuminate\Http\Response
     */
    public function edit(invoice_detail $invoice_detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoice_detail  $invoice_detail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoice_detail $invoice_detail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoice_detail  $invoice_detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $invoices = Invoice_attachment::findOrFail($request->id_file);
        Storage::disk('public_uploads')->delete('/'.$invoices->invoice_num.'/'.$invoices->file_name);
        $invoices->delete();
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }

    public function getDetails($id){
        $invoices = invoice::where('id', $id)->first();
        $details = invoice_detail::where('invoice_id',$id)->get();
        $attachments = Invoice_attachment::where('invoice_id',$id)->get();
        return view('invoices.invoice_detail',compact('invoices','details','attachments'));
    }

    public function openFile( $invoice_number , $file_name ){
        $st="Attachments";
        $files = public_path($st.'/'.$invoice_number.'/'.$file_name);
        return response()->file($files);
    }

    public function downloadFile( $invoice_number , $file_name ){
        $st="Attachments";
        $files = public_path($st.'/'.$invoice_number.'/'.$file_name);
        return response()->download($files);
    }
}
