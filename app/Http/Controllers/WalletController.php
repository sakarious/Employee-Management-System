<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transfer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;

class WalletController extends Controller
{
    public function getWallet(Request $request){
        $user_id = $request->user()->id;

        $wallet = Wallet::where('user_id', $user_id)->firstOrFail();
 
        $response = [
            'status' => 'Success',
            'message' => "Wallet Details",
            'wallet' => $wallet
        ];

        return response()->json($response, 200);
    }

    public function transfer(Request $request){
        $validData = $request->validate([
            'transfers' => 'required|array'
        ]);

        $transfers = $validData['transfers'];

        $no_of_transfers = count($transfers);
        $success = 0;
        $failed = 0;

        $insufficient_funds = false;

        foreach($transfers as $transfer){
            $funds = Wallet::where('user_id', $request->user()->id)->firstOrFail();
            //Check if funds is greater than amount to ve transfered
            if($funds->balance >= $transfer['amount']){
                $success = Wallet::where('user_id', $transfer['wallet'])->update(['balance' => Wallet::raw('balance + '.$transfer['amount'])]);
                if($success){
                    //Update Admin Balance
                    $funds = Wallet::where('user_id', $request->user()->id)->update(['balance' => Wallet::raw('balance - '.$transfer['amount'])]);
                    //Create Transfer Record
                    $successfulTransfer = Transfer::create([
                        'user_id' => $transfer['wallet'],
                        'amount' => $transfer['amount'],
                        'sent' => $request->user()->id,
                        'status' => 'success'
                    ]);
                    $success = $success + 1;
                } else {
                    $failed = $failed + 1;
                    $failedTransfer = Transfer::create([
                        'user_id' => $transfer['wallet'],
                        'amount' => $transfer['amount'],
                        'sent' => $request->user()->id,
                        'status' => 'failed'
                    ]);
                }
            } else {
                $insufficient_funds = true;
            }
        }

        if($insufficient_funds == false){
            $response = [
                'status' => 'Success',
                'message' => "Transfer Processed",
                'all' => $no_of_transfers,
                'successful' => $no_of_transfers - $failed,
                'failed' => $failed,
            ];
        } else {
            $response = [
                'message' => 'Insufficient Funds'
            ];
        }

        return response()->json($response, 200);
    }

    public function transfer_history(Request $request){
        //For Admin
        if($request->user()->is_admin == 1){
            if($request->query('user')){
                $transfers = Transfer::where('user_id', $request->query('user'))->where('sent', $request->user()->id)->get();
            } else if($request->query('status')){
                $transfers = Transfer::where('status', $request->query('status'))->where('sent', $request->user()->id)->get();
            } else {
                $transfers = Transfer::all();
            }

        } else {
            //For Users
            $user = $request->user()->id;
            $transfers = Transfer::where('user_id', $user)->get();
        }


        $response = [
            'status' => 'Success',
            'message' => "Transfer History",
            'data' => $transfers
        ];

        return response()->json($response, 200);
    }
}
