<?php

namespace App\Http\Controllers;

use App\Enums\CategoryType;
use App\Helpers\ResponseHelper;
use App\Models\Category;
use App\Models\ChartOfAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ChartOfAccountController extends Controller
{
    
    public function index() {
        $chartOfAccounts = ChartOfAccount::with('category')->where('user_id', Auth::user()->id)->get();
        return view('coa.index', compact('chartOfAccounts'));
    }

    public function create() {
        $categories = Category::where('user_id', Auth::user()->id)->get();
        $categoryOptions = collect($categories)->pluck('name', 'name')->toArray(); 

        return view('coa.create', compact('categoryOptions'));
    }

    public function store(Request $request) {
        try {

            $validator = Validator::make($request->all(), [
                'code' => ['required', 'unique:chart_of_accounts', 'integer'],
                'name' => ['required'],
                'category_name' => ['required']
            ]);

            if ($validator->fails()) {
                return ResponseHelper::SendValidationError($validator->errors());
            }

            ChartOfAccount::create([
                'user_id' => Auth::user()->id,
                'code' => $request->code,
                'name' => $request->name,
                'category_name' => $request->category_name
            ]);

            return ResponseHelper::SendSuccess("create coa successfully");

        } catch(Exception $error) {
            return ResponseHelper::SendInternalServerError($error);
        }
    }

}
