<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transactions;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function dashboard(){
        $trans = Transactions::orderBy('created_at', 'DESC')->whereBetween('created_at', [Carbon::today(), Carbon::now()]);
        $wallet_balance = User::sum('wallets');
        $earnings_balance = User::sum('cashback');
        $transaction = $trans->latest()->limit(20);
        $clone1 = clone $transaction;
        $clone2 = clone $transaction;
        $clone3 = clone $transaction;

        $transactions1 = clone $trans;
        $transactions2 = clone $trans;
        $transactions3 = clone $trans;
        $transactions4 = clone $trans;
        $transactions5 = clone $trans;
        $transactions6 = clone $trans;

        $t_pending = $transactions1->whereIn('buyer_status', ['ATTENTION-REQUIRED', 'PENDING']);
        $t_all = $transactions6;

        $t_success = $transactions2->whereIn('buyer_status', ['DELIVERED', 'SUCCESSFUL']);
        $t_failed = $transactions3->whereIn('buyer_status', ['FAILED']);
        $t_profit = $transactions4->sum('profit');
        $t_profit = $transactions4->sum('profit');

        $pending = $clone1->whereIn('buyer_status', ['ATTENTION-REQUIRED', 'PENDING'])->get();
        $failed = $clone2->whereIn('buyer_status', ['FAILED'])->get();
        $success = $clone3->whereIn('buyer_status',['DELIVERED', 'SUCCESSFUL'])->get();
        $customers = User::count();

        return view('admin.dashboard', compact('t_profit','pending','failed','success', 'wallet_balance', 'earnings_balance','t_pending','t_success','t_failed','customers', 't_all'));
    }
}
