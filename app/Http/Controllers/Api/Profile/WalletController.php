<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Notifications\NewWithdrawalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallet = Wallet::whereUserId(auth('api')->id())->where('active', 1)->first();
        return response()->json(['wallet' => $wallet], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function myWallet()
    {
        $wallets = Wallet::whereUserId(auth('api')->id())->get();
        return response()->json(['wallets' => $wallets], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function setWalletActive($id)
    {
        $checkWallet = Wallet::whereUserId(auth('api')->id())->where('active', 1)->first();
        if (! empty($checkWallet)) {
            $checkWallet->update(['active' => 0]);
        }

        $wallet = Wallet::find($id);
        if (! empty($wallet)) {
            $wallet->update(['active' => 1]);
            return response()->json(['wallet' => $wallet], 200);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestWithdrawal(Request $request)
    {
        $setting = Setting::first();

        // Проверяем авторизацию пользователя (аффилиат или кто-либо)
        if (auth('api')->check()) {

            // Валидация по типу (pix или bank)
            if ($request->type === 'pix') {
                $rules = [
                    'amount'       => ['required', 'numeric', 'min:'.$setting->min_withdrawal, 'max:'.$setting->max_withdrawal],
                    'pix_type'     => 'required',
                    'accept_terms' => 'required',
                ];

                switch ($request->pix_type) {
                    case 'document':
                        $rules['pix_key'] = 'required|cpf_ou_cnpj';
                        break;
                    case 'email':
                        $rules['pix_key'] = 'required|email';
                        break;
                    case 'phoneNumber':
                        $rules['pix_key'] = 'required|telefone';
                        break;
                    default:
                        $rules['pix_key'] = 'required';
                        break;
                }
            }

            if ($request->type === 'bank') {
                $rules = [
                    'amount'       => ['required', 'numeric', 'min:'.$setting->min_withdrawal, 'max:'.$setting->max_withdrawal],
                    'bank_info'    => 'required',
                    'accept_terms' => 'required',
                ];
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            // Проверка лимитов на вывод
            if (! empty($setting->withdrawal_limit)) {
                switch ($setting->withdrawal_period) {
                    case 'daily':
                        $registrosDiarios = Withdrawal::whereDate('created_at', now()->toDateString())->count();
                        if ($registrosDiarios >= $setting->withdrawal_limit) {
                            return response()->json(['error' => trans('Вы уже достигли суточного лимита на вывод средств')], 400);
                        }
                        break;
                    case 'weekly':
                        $registrosDiarios = Withdrawal::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
                        if ($registrosDiarios >= $setting->withdrawal_limit) {
                            return response()->json(['error' => trans('Вы уже достигли недельного лимита на вывод средств')], 400);
                        }
                        break;
                    case 'monthly':
                        $registrosDiarios = Withdrawal::whereYear('created_at', now()->year)
                            ->whereMonth('data', now()->month)
                            ->count();
                        if ($registrosDiarios >= $setting->withdrawal_limit) {
                            return response()->json(['error' => trans('Вы уже достигли месячного лимита на вывод средств')], 400);
                        }
                        break;
                    case 'yearly':
                        $registrosDiarios = Withdrawal::whereYear('created_at', now()->year)->count();
                        if ($registrosDiarios >= $setting->withdrawal_limit) {
                            return response()->json(['error' => trans('Вы уже достигли годового лимита на вывод средств')], 400);
                        }
                        break;
                }
            }

            // Снова проверяем max_withdrawal
            if ($request->amount > $setting->max_withdrawal) {
                return response()->json(['error' => 'Вы превысили максимально допустимый лимит в: '.$setting->max_withdrawal], 400);
            }

            // Проверяем согласие с условиями
            if ($request->accept_terms == true) {
                if (floatval($request->amount) > floatval(auth('api')->user()->wallet->balance_withdrawal)) {
                    return response()->json(['error' => 'У вас недостаточно средств на балансе'], 400);
                }

                $data = [];
                if ($request->type === 'pix') {
                    $data = [
                        'user_id'  => auth('api')->user()->id,
                        'amount'   => \Helper::amountPrepare($request->amount),
                        'type'     => $request->type,
                        'pix_key'  => $request->pix_key,
                        'pix_type' => $request->pix_type,
                        'currency' => $request->currency,
                        'symbol'   => $request->symbol,
                        'status'   => 0,
                    ];
                }

                if ($request->type === 'bank') {
                    $data = [
                        'user_id'   => auth('api')->user()->id,
                        'amount'    => \Helper::amountPrepare($request->amount),
                        'type'      => $request->type,
                        'bank_info' => $request->bank_info,
                        'currency'  => $request->currency,
                        'symbol'    => $request->symbol,
                        'status'    => 0,
                    ];
                }

                $withdrawal = Withdrawal::create($data);

                if ($withdrawal) {
                    $wallet = Wallet::where('user_id', auth('api')->id())->first();
                    $wallet->decrement('balance_withdrawal', floatval($request->amount));

                    $admins = User::where('role_id', 0)->get();
                    foreach ($admins as $admin) {
                        $admin->notify(new NewWithdrawalNotification(auth()->user()->name, $request->amount));
                    }

                    return response()->json([
                        'status'  => true,
                        'message' => 'Вывод успешно выполнен',
                    ], 200);
                }
            }

            return response()->json(['error' => 'Вам необходимо принять условия'], 400);
        }

        return response()->json(['error' => 'Ошибка при выполнении вывода'], 400);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
