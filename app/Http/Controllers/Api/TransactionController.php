<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use Carbon\Carbon;
use DB;

class TransactionController extends Controller
{
    public function getTransations(Request $request)
    {
        $data = $request->validate([
            'date' => 'required',
        ]);

        $user_id = auth()->user()->id;

        // user requested date
        $parse = Carbon::parse($data['date']);

        $income_amount = Income::select('amount')
            ->where('user_id', '=', $user_id)
            ->whereDate('date', '=', $parse->format('y-m-d'))
            ->get()
            ->sum('amount');

        $expense_Amount = Expense::select('amount')
            ->where('user_id', '=', $user_id)
            ->whereDate('date', '=', $parse->format('y-m-d'))
            ->get()
            ->sum('amount');

        $income = Income::where(['user_id' => $user_id])
            ->whereDate('date', '=', $parse->format('y-m-d'))
            ->orderBy('created_at', 'ASC')
            ->get();

        $expense = Expense::where(['user_id' => $user_id])
            ->whereDate('date', '=', $parse->format('y-m-d'))
            ->orderBy('created_at', 'ASC')
            ->get();
        $merged = array_merge($income->toArray(), $expense->toArray());

        return response()->json([
            'status' => 200,
            'message' => 'Successfully get all transactions',
            'daily_total_income' => $income_amount,
            'daily_total_expense' => $expense_Amount,
            'data' => $merged,
        ]);
    }

    public function totalIncomeAndExpense(Request $request)
    {
        $data = $request->validate([
            // 'date' => 'required',
        ]);

        $user_id = auth()->user()->id;
        $income_total = Income::select('amount')
            ->where('user_id', '=', $user_id)
            ->get();
        $it = $income_total->sum('amount');
        $expense_total = Expense::select('amount')
            ->where('user_id', '=', $user_id)
            ->get();
        $et = $expense_total->sum('amount');
        return response()->json([
            'status' => 200,
            'message' => 'Successfully get total income',
            'total_income' => $it,
            'total_expense' => $et,
        ]);
    }

    public function getMonthlyWiseStatistics(Request $request)
    {
        $data = $request->validate([
            'month' => 'required',
            'year' => 'required',
        ]);
        $user_id = auth()->user()->id;

        $income = Income::select('amount')
            ->where('user_id', '=', $user_id)
            ->whereMonth('date', '=', $data['month'])
            ->whereYear('date', '=', $data['year'])
            ->get()
            ->sum('amount');

        $expense = Expense::select('amount')
            ->where('user_id', '=', $user_id)
            ->whereMonth('date', '=', $data['month'])
            ->whereYear('date', '=', $data['year'])
            ->get()
            ->sum('amount');

        $c_e_amount = Expense::select(
            'expense_category',
            DB::raw('SUM(amount) as amount')
        )
            ->where('user_id', '=', $user_id)
            ->whereMonth('date', '=', $data['month'])
            ->whereYear('date', '=', $data['year'])
            ->groupBy('expense_category')
            ->get();

        $data = Expense::where(['user_id' => $user_id])
            ->whereMonth('date', '=', $data['month'])
            ->whereYear('date', '=', $data['year'])
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'message' => 'Successfully get monthly expense',
            'monthly_income' => $income,
            'monthly_expense' => $expense,
            'category_expense_amount' => $c_e_amount,
            'data' => $data,
        ]);
    }
}
