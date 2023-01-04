<?php

namespace App\Http\Controllers\ui;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $from_warehouse_id = '1';
        $to_warehouse_id = '2';
        $transfer = DB::table('tbl_transfers')->where('to_warehouse_id', 2)->orderByDesc('id')->first();
        $invoice = 1;
        if ($transfer != null && $transfer->reference_no != '') {
            $ref = explode("-", $transfer->reference_no);
            $invoice = (int)$ref[2] + 1;
        }
        $invoice_increase = str_pad($invoice, 7, "0", STR_PAD_LEFT);

        $transfer_id = DB::table('tbl_transfers')->insertGetId([
            'reference_no' => 'TNF-' . date('Ymd-') . $invoice_increase,
            'user_id' => Auth::user()->user_id,
            'status' => '1',
            'from_warehouse_id' => $from_warehouse_id,
            'to_warehouse_id' => $to_warehouse_id,
            'item' => 0,
            'total_qty' => 0,
            'total_tax' => 0,
            'total_cost' => 0,
            'shipping_cost' => 0,
            'grand_total' => 0,
            'document' => 'file.jpg',
            'note' => 'transfer from main store',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $product_transfer = [
            [
                'product_id' => '5',
                'qty' => 5,
                'cost' => 2000,
                'tax' => 5,
                'total' => 100
            ],
            [
                'product_id' => '6',
                'qty' => 6,
                'cost' => 4000,
                'tax' => 0,
                'total' => 100
            ],
            [
                'product_id' => '7',
                'qty' => 7,
                'cost' => 5000,
                'tax' => 0,
                'total' => 100
            ],
            [
                'product_id' => '8',
                'qty' => 8,
                'cost' => 6000,
                'tax' => 0,
                'total' => 100
            ],
            [
                'product_id' => '9',
                'qty' => 9,
                'cost' => 7000,
                'tax' => 0,
                'total' => 100
            ],
            [
                'product_id' => '18',
                'qty' => 18,
                'cost' => 12000,
                'tax' => 0,
                'total' => 100
            ]
        ];

        $total_qty = 0;
        $total_tax = 0;
        $total_cost = 0;
        $grand_total = 0;

        foreach ($product_transfer as $i) {
            $product = DB::table(TableProduct)->find($i['product_id']);
            $total = $i['qty'] * $i['cost'];
            $tax = $total * $i['tax'] / 100;

            $total_qty = $total_qty + $i['qty'];
            $total_tax = $total_tax + $tax;
            $total_cost = $total_cost + $i['cost'];
            $grand_total = $grand_total + $total;

            if ($product != null) {
                DB::table('tbl_product_transfer')->insert([
                    'transfer_id' => $transfer_id,
                    'product_id' => $i['product_id'],
                    'qty' => $i['qty'],
                    'purchase_unit_id' => '0',
                    'net_unit_cost' => $i['cost'],
                    'tax_rate' => $i['tax'],
                    'tax' => $tax,
                    'total' => $total + $tax,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        DB::table('tbl_transfers')
            ->where('id', $transfer_id)
            ->update([
                'item' => count($product_transfer),
                'total_qty' => $total_qty,
                'total_tax' => $total_tax,
                'total_cost' => $total_cost,
                'grand_total' => $grand_total
            ]);

        return $transfer_id;

        return 'transfers';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('transfers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
