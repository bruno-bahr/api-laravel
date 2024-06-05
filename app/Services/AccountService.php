<?php

namespace App\Services;

use App\Models\Accounts;

class AccountService
{
    public function handleDeposit(array $data)
    {
        $accountId = $data['destination'];
        $amount = $data['amount'];

        $account = Accounts::find($accountId);

        if (!$account) {
            $account = new Accounts;
            $account->id = $accountId;
            $account->balance = $amount;
            $account->save();
        }else{
            $account->balance += $amount;
            $account->save(); 
        }

        

        return [
            'destination' => [
                'id' => $accountId,
                'balance' => $account->balance,
            ],
        ];
    }

    public function handleWithdraw(array $data)
    {
        $accountId = $data['origin'] ?? null;
        $amount = (float) $data['amount'];

        $account = Accounts::find($accountId);

        if (!$account) {
            return null;
        }

        if (isset($account) && $account->balance >= $amount) {
            $account->balance -= $amount;
            $account->save();
        }

        return [
            'origin' => [
                'id' => $accountId,
                'balance' => $account->balance,
            ],
        ];
    }



    public function handleTransfer(array $data)
    {
        $originAccountId = $data['origin'];
        $destinationAccountId = $data['destination'];
        $amount = (float) $data['amount'];

        // Find accounts
        $originAccount = Accounts::find($originAccountId);
        $destinationAccount = Accounts::find($destinationAccountId);

        // Check for non-existent account
        if (!isset($originAccount) || ($originAccount->balance < $amount)) {
            return null;
        }
        if (!isset($destinationAccount)) {
            $destinationAccount = new Accounts;
            $destinationAccount->id = $destinationAccountId;
            $destinationAccount->balance = $amount;
            $destinationAccount->save();
        }

        // Update origin and destination balances
        $originAccount->balance -= $amount;
        $originAccount->save();

        // Prepare successful transfer response
        return [
            'origin' => [
                'id' => $originAccountId,
                'balance' => $originAccount->balance,
            ],
            'destination' => [
                'id' => $destinationAccountId,
                'balance' => $destinationAccount->balance, 
            ],
        ];
    }


    public function handleBalance(int $account_id)
    {
        $account = Accounts::find($account_id);

        return $account != null ? $account->balance : null;
    }
}
