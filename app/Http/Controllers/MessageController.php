<?php

namespace App\Http\Controllers;

use Validator;
use App\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function index()
    {
        return response()->json([
            'messages' => Message::paginate(10),
            'success' => true
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:100',
            'phone' => 'required|max:20',
            'email' => 'required|string|email|max:255',
            'message' => 'required|string|max:300'
        ];

        $validator = Validator::make(request()->json()->all(), $rules);
        if($validator->fails())
        {
            return response()->json([
                'errors' => $validator->errors(),
                'success' => false
            ], 400);
        }

        $message = Message::create([
            'name' => $request->json()->get('name'),
            'phone' => $request->json()->get('phone'),
            'email' => $request->json()->get('email'),
            'message' => $request->json()->get('message'),
        ]);

        return response()->json([
            'success' => true,
            'message' => $message
        ], 200);
    }
}
