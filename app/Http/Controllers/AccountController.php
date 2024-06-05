<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use Illuminate\Http\Request;
use App\Services\AccountService;

class AccountController extends Controller
{

    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function reset()
    {
      Accounts::truncate();
      return "OK";
    }
    

    public function balance(Request $request)
    {
        $accountId = $request->query('account_id'); 

        if (is_null($accountId)) {
            return response()->json(['message' => 'Missing account_id parameter'], 400);
        }

        $balance = $this->accountService->handleBalance($accountId);

        return $balance !== null ?
            response()->json($balance, 200) :
            response()->json(0, 404);
    }

    public function event(Request $req)
    {
        $data = $req->all();

        switch ($data['type']) {
            case ('deposit'):
                $response = $this->accountService->handleDeposit($data);
                break;
            case ('withdraw'):
                $response = $this->accountService->handleWithdraw($data);
                break;
            case ('transfer'):
                $response = $this->accountService->handleTransfer($data);
                break;
            default:
                return response()->json(['message' => 'Invalid transaction type'], 400);
        }

        if($response != null )
           return response()->json($response, 201); 
        else
            return response()->json(0, 404); 
        
    }
}
