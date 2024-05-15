<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use DataTables;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [
            'code'       => uniqid(date("Ymd")),
            'saldo'      => number_format(Saldo::where('users_id', auth()->user()->id)->first()->saldo),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_form_transaction',
            'title'      => 'Form Transaction'
        ];
        return view('layouts.v_template', $data);
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
        DB::beginTransaction();
        try {
            $file = $request->file('file_bukti');
            $amount = str_replace(",", "", $request->amount);
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $fileName);
            $transData = [
                'code' => $request->code,
                'type' => $request->type,
                'amount' => $amount,
                'note' => $request->note,
                'file' => $fileName,
            ];
            Transaction::create($transData);
            $saldo = Saldo::select('*')->where('users_id', auth()->user()->id)->first();
            if (empty($saldo)) {
                $saldo = new Saldo();
                $saldo->users_id = auth()->user()->id;
                $saldo->saldo = ($request->type == 2 ? '-' . (int)$amount : (int)$amount);
                $saldo->save();
            } else {
                $saldo->saldo = ($request->type == 2 ? '-' . (int)$amount + $saldo->saldo : (int)$amount) + $saldo->saldo;
                $saldo->save();
            }
            DB::commit();
            return response()->json([
                'status' => 200,
            ]);
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
