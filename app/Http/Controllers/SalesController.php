<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sale::all();
        return view('sales.index',compact('sales'));
    }

    public function create()
    {
        $customers = Customer::all();
        $warehouses = Warehouse::all();
        return view('sales.create',compact('customers','warehouses'));
    }

    public function store(Request $request)
    {
        // $data = $request->all();
        // dd($data);
        // return;


        if(!$request['created_at'])
            $date = date("Y-m-d H:i:s");
        else{
            $date = date("Y-m-d H:i:s", strtotime($request->created_at));
        }

        $sub_total = array_sum($request->sub_total);
        $discount = $request->discount_value;
        if($request->discount_type = 'percentage'){
            $discount = $sub_total * ($request->discount_value/100);
        }
        $shipping = $request->shipping_cost;

        $tax_rate = $request->tax_rate;
        $tax_value = ($sub_total - $discount + $shipping) * ($request->tax_rate/100) ;

        $grand_total = $sub_total - $discount + $shipping + $tax_value;

        $paid_amount = '';
        $recieved_amount = '';
        if(!$request->paid_amount){
           $paid_amount = 0;
        }
        if(!$request->recieved_amount){
            $recieved_amount = 0;
        }

        $sale = Sale::create([
            'customer_id' => $request->customer_id,
            'warehouse_id' => $request->warehouse_id,
            'user_id' => Auth::user()->id,
            'total_qty' => array_sum($request->qty),
            'total_price' => $sub_total,
            'grand_total' => $grand_total,
            'tax_rate' => $request->tax_rate,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'shipping_cost' => $request->shipping_cost,
            'payment_status' => $request->payment_status,
            'paid_amount' => $paid_amount,
            // 'recieved_amount' => $recieved_amount,
            'items_count' => count($request->product_ids),
            'note' => $request->note,
            'created_at' => $date,
        ]);

        return redirect()->back()->with('sale',$sale);
        // $cash_register_data = CashRegister::where([
        //     ['user_id', $data['user_id']],
        //     ['warehouse_id', $data['warehouse_id']],
        //     ['status', true]
        // ])->first();

        // if($cash_register_data)
        //     $data['cash_register_id'] = $cash_register_data->id;

        // if(isset($data['created_at']))
        //     $data['created_at'] = date("Y-m-d H:i:s", strtotime($data['created_at']));
        // else
        //     $data['created_at'] = date("Y-m-d H:i:s");

        // return dd($data);


        // if($data['pos']) {
        //     // if(!isset($data['reference_no']))
        //     //     $data['reference_no'] = 'posr-' . date("Ymd") . '-'. date("his");

        //     $balance = $data['grand_total'] - $data['paid_amount'];
        //     if($balance > 0 || $balance < 0)
        //         $data['payment_status'] = 2;
        //     else
        //         $data['payment_status'] = 4;


        //         // undefined index 'draft'
        //     // if($data['draft']) {
        //     //     return 'a7a';
        //     //     $sale_data = Sale::find($data['sale_id']);
        //     //     $lims_product_sale_data = Product_Sale::where('sale_id', $data['sale_id'])->get();
        //     //     foreach ($lims_product_sale_data as $product_sale_data) {
        //     //         $product_sale_data->delete();
        //     //     }
        //     //     $sale_data->delete();
        //     // }
        //     // return 'a7ten';
        // }
        // else {
        //     // return '3 a7at';
        //     // if(!isset($data['reference_no']))
        //     //     $data['reference_no'] = 'sr-' . date("Ymd") . '-'. date("his");
        // }

        // $document = $request->document;

        // if ($document) {
        //     $v = Validator::make(
        //         [
        //             'extension' => strtolower($request->document->getClientOriginalExtension()),
        //         ],
        //         [
        //             'extension' => 'in:jpg,jpeg,png,gif,pdf,csv,docx,xlsx,txt',
        //         ]
        //     );
        //     if ($v->fails())
        //         return redirect()->back()->withErrors($v->errors());

        //     $documentName = $document->getClientOriginalName();
        //     $document->move('public/sale/documents', $documentName);
        //     $data['document'] = $documentName;
        // }

        // if($data['coupon_active']) {
        //     $lims_coupon_data = Coupon::find($data['coupon_id']);
        //     $lims_coupon_data->used += 1;
        //     $lims_coupon_data->save();
        // }

        // dd($data);
        $sale_data = Sale::create($data);
        $lims_customer_data = Customer::find($data['customer_id']);
        $lims_reward_point_setting_data = RewardPointSetting::latest()->first();
        //checking if customer gets some points or not
        if($lims_reward_point_setting_data->is_active &&  $data['grand_total'] >= $lims_reward_point_setting_data->minimum_amount) {
            $point = (int)($data['grand_total'] / $lims_reward_point_setting_data->per_point_amount);
            $lims_customer_data->points += $point;
            $lims_customer_data->save();
        }

        //collecting male data
        $mail_data['email'] = $lims_customer_data->email;
        $mail_data['reference_no'] = $sale_data->reference_no;
        $mail_data['sale_status'] = $sale_data->sale_status;
        $mail_data['payment_status'] = $sale_data->payment_status;
        $mail_data['total_qty'] = $sale_data->total_qty;
        $mail_data['total_price'] = $sale_data->total_price;
        $mail_data['order_tax'] = $sale_data->order_tax;
        $mail_data['order_tax_rate'] = $sale_data->order_tax_rate;
        $mail_data['order_discount'] = $sale_data->order_discount;
        $mail_data['shipping_cost'] = $sale_data->shipping_cost;
        $mail_data['grand_total'] = $sale_data->grand_total;
        $mail_data['paid_amount'] = $sale_data->paid_amount;

        $product_id = $data['product_id'];
        $product_batch_id = $data['product_batch_id'];
        $imei_number = $data['imei_number'];
        $product_code = $data['product_code'];
        $qty = $data['qty'];
        $sale_unit = $data['sale_unit'];
        $net_unit_price = $data['net_unit_price'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];
        $product_sale = [];

        foreach ($product_id as $i => $id) {
            $lims_product_data = Product::where('id', $id)->first();
            $product_sale['variant_id'] = null;
            $product_sale['product_batch_id'] = null;
            if($lims_product_data->type == 'combo' && $data['sale_status'] == 1){
                $product_list = explode(",", $lims_product_data->product_list);
                $variant_list = explode(",", $lims_product_data->variant_list);
                if($lims_product_data->variant_list)
                    $variant_list = explode(",", $lims_product_data->variant_list);
                else
                    $variant_list = [];
                $qty_list = explode(",", $lims_product_data->qty_list);
                $price_list = explode(",", $lims_product_data->price_list);

                foreach ($product_list as $key=>$child_id) {
                    $child_data = Product::find($child_id);
                    if(count($variant_list) && $variant_list[$key]) {
                        $child_product_variant_data = ProductVariant::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$key]]
                        ])->first();

                        $child_warehouse_data = Product_Warehouse::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$key]],
                            ['warehouse_id', $data['warehouse_id'] ],
                        ])->first();

                        $child_product_variant_data->qty -= $qty[$i] * $qty_list[$key];
                        $child_product_variant_data->save();
                    }
                    else {
                        $child_warehouse_data = Product_Warehouse::where([
                            ['product_id', $child_id],
                            ['warehouse_id', $data['warehouse_id'] ],
                        ])->first();
                    }

                    $child_data->qty -= $qty[$i] * $qty_list[$key];
                    $child_warehouse_data->qty -= $qty[$i] * $qty_list[$key];

                    $child_data->save();
                    $child_warehouse_data->save();
                }
            }

            if($sale_unit[$i] != 'n/a') {
                $lims_sale_unit_data  = Unit::where('unit_name', $sale_unit[$i])->first();
                $sale_unit_id = $lims_sale_unit_data->id;
                if($lims_product_data->is_variant) {
                    $lims_product_variant_data = ProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($id, $product_code[$i])->first();
                    $product_sale['variant_id'] = $lims_product_variant_data->variant_id;
                }
                if($lims_product_data->is_batch && $product_batch_id[$i]) {
                    $product_sale['product_batch_id'] = $product_batch_id[$i];
                }

                if($data['sale_status'] == 1) {
                    if($lims_sale_unit_data->operator == '*')
                        $quantity = $qty[$i] * $lims_sale_unit_data->operation_value;
                    elseif($lims_sale_unit_data->operator == '/')
                        $quantity = $qty[$i] / $lims_sale_unit_data->operation_value;
                    //deduct quantity
                    $lims_product_data->qty = $lims_product_data->qty - $quantity;
                    $lims_product_data->save();
                    //deduct product variant quantity if exist
                    if($lims_product_data->is_variant) {
                        $lims_product_variant_data->qty -= $quantity;
                        $lims_product_variant_data->save();
                        $warehouse_products = Product_Warehouse::FindProductWithVariant($id, $lims_product_variant_data->variant_id, $data['warehouse_id'])->first();
                    }
                    elseif($product_batch_id[$i]) {
                        $warehouse_products = Product_Warehouse::where([
                            ['product_batch_id', $product_batch_id[$i] ],
                            ['warehouse_id', $data['warehouse_id'] ]
                        ])->first();
                        $lims_product_batch_data = ProductBatch::find($product_batch_id[$i]);
                        //deduct product batch quantity
                        $lims_product_batch_data->qty -= $quantity;
                        $lims_product_batch_data->save();
                    }
                    else {
                        $warehouse_products = Product_Warehouse::FindProductWithoutVariant($id, $data['warehouse_id'])->first();
                    }
                    //deduct quantity from warehouse
                    $warehouse_products->qty -= $quantity;
                    $warehouse_products->save();
                }
            }
            else
                $sale_unit_id = 0;

            if($product_sale['variant_id']) {
                $variant_data = Variant::select('name')->find($product_sale['variant_id']);
                $mail_data['products'][$i] = $lims_product_data->name . ' ['. $variant_data->name .']';
            }
            else
                $mail_data['products'][$i] = $lims_product_data->name;
            //deduct imei number if available
            if($imei_number[$i]) {
                $imei_numbers = explode(",", $imei_number[$i]);
                $all_imei_numbers = explode(",", $warehouse_products->imei_number);
                foreach ($imei_numbers as $number) {
                    if (($j = array_search($number, $all_imei_numbers)) !== false) {
                        unset($all_imei_numbers[$j]);
                    }
                }
                $warehouse_products->imei_number = implode(",", $all_imei_numbers);
                $warehouse_products->save();
            }
            if($lims_product_data->type == 'digital')
                $mail_data['file'][$i] = url('/public/product/files').'/'.$lims_product_data->file;
            else
                $mail_data['file'][$i] = '';
            if($sale_unit_id)
                $mail_data['unit'][$i] = $lims_sale_unit_data->unit_code;
            else
                $mail_data['unit'][$i] = '';

            $product_sale['sale_id'] = $sale_data->id ;
            $product_sale['product_id'] = $id;
            $product_sale['imei_number'] = $imei_number[$i];
            $product_sale['qty'] = $mail_data['qty'][$i] = $qty[$i];
            $product_sale['sale_unit_id'] = $sale_unit_id;
            $product_sale['net_unit_price'] = $net_unit_price[$i];
            $product_sale['discount'] = $discount[$i];
            $product_sale['tax_rate'] = $tax_rate[$i];
            $product_sale['tax'] = $tax[$i];
            $product_sale['total'] = $mail_data['total'][$i] = $total[$i];
            Product_Sale::create($product_sale);
        }
        if($data['sale_status'] == 3)
            $message = 'Sale successfully added to draft';
        else
            $message = ' Sale created successfully';
        if($mail_data['email'] && $data['sale_status'] == 1) {
            try {
                Mail::send( 'mail.sale_details', $mail_data, function( $message ) use ($mail_data)
                {
                    $message->to( $mail_data['email'] )->subject( 'Sale Details' );
                });
            }
            catch(\Exception $e){
                $message = ' Sale created successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        }

        if($data['payment_status'] == 3 || $data['payment_status'] == 4 || ($data['payment_status'] == 2 && $data['pos'] && $data['paid_amount'] > 0)) {

            $lims_payment_data = new Payment();
            $lims_payment_data->user_id = Auth::id();

            if($data['paid_by_id'] == 1)
                $paying_method = 'Cash';
            elseif ($data['paid_by_id'] == 2) {
                $paying_method = 'Gift Card';
            }
            elseif ($data['paid_by_id'] == 3)
                $paying_method = 'Credit Card';
            elseif ($data['paid_by_id'] == 4)
                $paying_method = 'Cheque';
            elseif ($data['paid_by_id'] == 5)
                $paying_method = 'Paypal';
            elseif($data['paid_by_id'] == 6)
                $paying_method = 'Deposit';
            elseif($data['paid_by_id'] == 7) {
                $paying_method = 'Points';
                $lims_payment_data->used_points = $data['used_points'];
            }

            if($cash_register_data)
                $lims_payment_data->cash_register_id = $cash_register_data->id;
            $lims_account_data = Account::where('is_default', true)->first();
            $lims_payment_data->account_id = $lims_account_data->id;
            $lims_payment_data->sale_id = $sale_data->id;
            $data['payment_reference'] = 'spr-'.date("Ymd").'-'.date("his");
            $lims_payment_data->payment_reference = $data['payment_reference'];
            $lims_payment_data->amount = $data['paid_amount'];
            $lims_payment_data->change = $data['paying_amount'] - $data['paid_amount'];
            $lims_payment_data->paying_method = $paying_method;
            $lims_payment_data->payment_note = $data['payment_note'];
            $lims_payment_data->save();

            $lims_payment_data = Payment::latest()->first();
            $data['payment_id'] = $lims_payment_data->id;
            if($paying_method == 'Credit Card'){
                $lims_pos_setting_data = PosSetting::latest()->first();
                Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
                $token = $data['stripeToken'];
                $grand_total = $data['grand_total'];

                $lims_payment_with_credit_card_data = PaymentWithCreditCard::where('customer_id', $data['customer_id'])->first();

                if(!$lims_payment_with_credit_card_data) {
                    // Create a Customer:
                    $customer = \Stripe\Customer::create([
                        'source' => $token
                    ]);

                    // Charge the Customer instead of the card:
                    $charge = \Stripe\Charge::create([
                        'amount' => $grand_total * 100,
                        'currency' => 'usd',
                        'customer' => $customer->id
                    ]);
                    $data['customer_stripe_id'] = $customer->id;
                }
                else {
                    $customer_id =
                    $lims_payment_with_credit_card_data->customer_stripe_id;

                    $charge = \Stripe\Charge::create([
                        'amount' => $grand_total * 100,
                        'currency' => 'usd',
                        'customer' => $customer_id, // Previously stored, then retrieved
                    ]);
                    $data['customer_stripe_id'] = $customer_id;
                }
                $data['charge_id'] = $charge->id;
                PaymentWithCreditCard::create($data);
            }
            elseif ($paying_method == 'Gift Card') {
                $lims_gift_card_data = GiftCard::find($data['gift_card_id']);
                $lims_gift_card_data->expense += $data['paid_amount'];
                $lims_gift_card_data->save();
                PaymentWithGiftCard::create($data);
            }
            elseif ($paying_method == 'Cheque') {
                PaymentWithCheque::create($data);
            }
            elseif ($paying_method == 'Paypal') {
                $provider = new ExpressCheckout;
                $paypal_data = [];
                $paypal_data['items'] = [];
                foreach ($data['product_id'] as $key => $product_id) {
                    $lims_product_data = Product::find($product_id);
                    $paypal_data['items'][] = [
                        'name' => $lims_product_data->name,
                        'price' => ($data['subtotal'][$key]/$data['qty'][$key]),
                        'qty' => $data['qty'][$key]
                    ];
                }
                $paypal_data['items'][] = [
                    'name' => 'Order Tax',
                    'price' => $data['order_tax'],
                    'qty' => 1
                ];
                $paypal_data['items'][] = [
                    'name' => 'Order Discount',
                    'price' => $data['order_discount'] * (-1),
                    'qty' => 1
                ];
                $paypal_data['items'][] = [
                    'name' => 'Shipping Cost',
                    'price' => $data['shipping_cost'],
                    'qty' => 1
                ];
                if($data['grand_total'] != $data['paid_amount']){
                    $paypal_data['items'][] = [
                        'name' => 'Due',
                        'price' => ($data['grand_total'] - $data['paid_amount']) * (-1),
                        'qty' => 1
                    ];
                }
                //return $paypal_data;
                $paypal_data['invoice_id'] = $sale_data->reference_no;
                $paypal_data['invoice_description'] = "Reference # {$paypal_data['invoice_id']} Invoice";
                $paypal_data['return_url'] = url('/sale/paypalSuccess');
                $paypal_data['cancel_url'] = url('/sale/create');

                $total = 0;
                foreach($paypal_data['items'] as $item) {
                    $total += $item['price']*$item['qty'];
                }

                $paypal_data['total'] = $total;
                $response = $provider->setExpressCheckout($paypal_data);
                 // This will redirect user to PayPal
                return redirect($response['paypal_link']);
            }
            elseif($paying_method == 'Deposit'){
                $lims_customer_data->expense += $data['paid_amount'];
                $lims_customer_data->save();
            }
            elseif($paying_method == 'Points'){
                $lims_customer_data->points -= $data['used_points'];
                $lims_customer_data->save();
            }
        }
        if($sale_data->sale_status == '1')
            return redirect('sales/gen_invoice/' . $sale_data->id)->with('message', $message);
        elseif($data['pos'])
            return redirect('pos')->with('message', $message);
        else
            return redirect('sales')->with('message', $message);
    }



    public function getProduct($id)
    {
        $warehouse_products = Product::join('warehouse_products', 'products.id', '=', 'warehouse_products.product_id')
        ->where([
            // ['products.is_active', true],
            ['warehouse_products.warehouse_id', $id],
            ['warehouse_products.qty', '>', 0]
        ])->get();

        return $warehouse_products;


    }

    public function productSearch(Request $request)
    {
        // return $request;
        $todayDate = date('Y-m-d');
        $product_code = explode("(", $request['data']);
        $product_info = explode("?", $request['data']);
        $customer_id = $product_info[1];
        $product_code[0] = rtrim($product_code[0], " ");
        $qty = $product_info[2];

        $product_data = Product::where([
            ['code', $product_code[0]],
            // ['is_active', true]
        ])->first();
        $product['id'] = $product_data->id;
        $product['name'] = $product_data->name;
        $product['code'] = $product_data->code;
        $product['price'] = $product_data->price;
        $product['unit'] = Unit::where("id", $product_data->unit_id)->first();
        $product['qty'] = $qty;
        return $product;

    }
}
