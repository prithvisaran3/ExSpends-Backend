<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;

class IncomeController extends Controller
{
    public function addIncome(Request $request)
    {
        $data = $request->validate([
            'income_name' => 'required',
            'amount' => 'required|min:2',
            'date' => 'required',
            'is_income' => 'required',
            'user_id' => '',
            'user_name' => '',
            'user_email' => '',
        ]);
        $token = auth()->user()->id;
        $name = auth()->user()->name;
        $email = auth()->user()->email;
        // return $name;
        $income = Income::create([
            'user_id' => $token,
            'user_name' => $name,
            'user_email' => $email,
            'income_name' => $data['income_name'],
            'amount' => $data['amount'],
            'date' => $data['date'],
            'is_income' => $data['is_income'],
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Income Added Successfully',
            'data' => $income,
        ]);
    }

    public function getIncome()
    {
        $user_id = auth()->user()->id;
        $in = Income::where(['user_id' => $user_id])->paginate(10);
        return response()->json([
            'status' => 200,
            'message' => 'Successfully get all income',
            'data' => $in,
        ]);
    }

    public function deleteIncome(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        if (auth()->user()) {
            $data = Income::find($request['id']);
            if ($data != null) {
                $data->delete();
                $list = Income::all()->toArray();
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'Successfully delete income',
                        'data' => $list,
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'status' => 404,
                        'message' => 'Already income deleted or no data found',
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
