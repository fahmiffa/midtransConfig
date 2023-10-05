<?php
 
namespace App\Services\Midtrans;
 
use Midtrans\Snap;
use Midtrans\Transaction;
 
class Trans extends Midtrans
{
    protected $order;
 
    public function __construct($order)
    {
        parent::__construct();
 
        $this->order = $order;
    }
 
    // snap paymanet
    public function getSnapToken()
    {

        $params = [
            'transaction_details' => [
                'order_id' => $this->order->inv,
                'name' => $this->order->services->name,
                'gross_amount' => $this->order->netto,
            ],
            'item_details' => [
                [
                    'id' => $this->order->inv,
                    'price' => $this->order->netto,
                    'quantity' => 1,
                    'name' => $this->order->services->name,
                ]
            ],
            'customer_details' => [
                'first_name' => $this->order->users->name,
                'email' => $this->order->users->email,
                'phone' => $this->order->users->hp,
            ]
        ];
 
    
        $snapToken = Snap::getSnapToken($params);        
 
        return $snapToken;
    }

    // redirect payment url midtrans
    public function createTransaction()
    {

        // info detail
        // $params = [
        //     'transaction_details' => [
        //         'order_id' => $this->order->inv,
        //         'name' => $this->order->services->name,
        //         'gross_amount' => $this->order->netto,
        //     ],
        //     'item_details' => [
        //         [
        //             'id' => $this->order->inv,
        //             'price' => $this->order->netto,
        //             'quantity' => 1,
        //             'name' => $this->order->services->name,
        //         ]
        //     ],
        //     'customer_details' => [
        //         'first_name' => $this->order->users->name,
        //         'email' => $this->order->users->email,
        //         'phone' => $this->order->users->hp,
        //     ]
        // ];
            
        $paymentUrl =  Snap::createTransaction($this->order)->redirect_url;            
        return $paymentUrl;
    }

    // status transaction midtrans
    public function statusTransaction()
    {
        return Transaction::status($this->order->order_id);
    }
}