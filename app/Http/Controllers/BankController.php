<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::orderby('created_at', 'DESC')->get();
        return view('admin.banks.index', compact('banks'));
    }

    public function create()
    {
        return view('admin.banks.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "bank_name" => "required",
            "account_name" => "required",
            "account_number" => "nullable",
        ]);

        $bank = Bank::updateOrCreate([
            "bank_name" => $request->bank_name,
            "account_name" => $request->account_name,
            "account_number" => $request->account_number,
        ]);

        return redirect(route('bank.index'))->with('message', 'Added successfully');
    }

    public function edit(Bank $bank)
    {
        return view('admin.banks.edit', compact('bank'));
    }

    public function update(Request $request, Bank $bank)
    {
        $this->validate($request, [
            "bank_name" => "required",
            "account_name" => "required",
            "account_number" => "nullable",
        ]);

        $bank->update([
            "bank_name" => $request->bank_name,
            "account_name" => $request->account_name,
            "account_number" => $request->account_number,
        ]);


        return back()->with('message', 'Updated successfully');
    }

    public function destroy(Bank $bank)
    {
        $bank->delete();
        return back()->with('message', 'Deleted successfully');
    }
}
