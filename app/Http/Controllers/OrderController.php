<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(['orders' => Order::paginate(10)]);
    }

    public function markAsOrdered($id)
    {
        // select the torder from the DB
        $order = Order::find($id);
        if(is_null($order))
        {
            // if the id given doesn't match any order an error message is returned
            return response()->json('order not found',404);
        }

        // setting the ordered proprety to true
        $order->ordered = true;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'order updated',
            'order' => $order
        ]);
    }

    public function destroy($id)
    {
        // select the torder from the DB
        $order = Order::find($id);
        if(is_null($order))
        {
            // if the id given doesn't match any order an error message is returned
            return response()->json('order not found',404);
        }

        // deleting the order
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'order deleted',
            'order' => $order
        ]);        
    }
}
