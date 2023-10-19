<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\User;
use App\Models\Income;
use DB;

class ExpenseController extends Controller
{
    public function addExpense(Request $request)
    {
        // validate
        $data = $request->validate([
            'expense_category' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'expense_name' => 'required',
            'is_income' => 'required',
        ]);

        //create

        $token = auth()->user()->id;
        $name = auth()->user()->name;
        $email = auth()->user()->email;

        $expense = Expense::create([
            'user_id' => $token,
            'user_email' => $email,
            'user_name' => $name,
            'expense_category' => $data['expense_category'],
            'amount' => $data['amount'],
            'date' => $data['date'],
            'expense_name' => $data['expense_name'],
            'is_income' => $data['is_income'],
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Expense Created Successfully',
            'data' => $expense,
        ]);
    }

    public function getExpense()
    {
        $user_id = auth()->user()->id;
        $expense = Expense::where(['user_id' => $user_id])->paginate(10);
        return response()->json([
            'status' => 200,
            'message' => 'Successfully get all expenses',
            'data' => $expense,
        ]);
    }

    public function deleteExpense(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        if (auth()->user()) {
            $data = Expense::find($request['id']);
            if ($data != null) {
                $data->delete();
                $list = Expense::all()->toArray();
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'Successfully delete expense',
                        'data' => $list,
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'status' => 404,
                        'message' =>
                            'Already expense deleted or no data found',
                    ],
                    404
                );
            }
        } else {
            return response()->json(
                [
                    'status' => 401,
                    'message' => 'Unauthorized or user deleted',
                ],
                401
            );
        }
    }
}
