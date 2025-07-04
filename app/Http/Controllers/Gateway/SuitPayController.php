<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Models\SuitPayPayment;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Traits\Gateways\SuitpayTrait;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class SuitPayController extends Controller
{
    use SuitpayTrait;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callbackMethodPayment(Request $request)
    {
        $data = $request->all();
        \DB::table('debug')->insert(['text' => json_encode($request->all())]);

        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function callbackMethod(Request $request)
    {
        $data = $request->all();

        //\DB::table('debug')->insert(['text' => json_encode($request->all())]);

        if (isset($data['idTransaction']) && $data['typeTransaction'] === 'PIX') {
            if ($data['statusTransaction'] === "PAID_OUT" || $data['statusTransaction'] === "PAYMENT_ACCEPT") {
                if (self::finalizePayment($data['idTransaction'])) {
                    return response()->json([], 200);
                }
            }
        }
    }

    /**
     * @param Request $request
     * @return null
     */
    public function getQRCodePix(Request $request)
    {
        return self::requestQrcode($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function consultStatusTransactionPix(Request $request)
    {
        return self::consultStatusTransaction($request);
    }

    /**
     * Display the specified resource.
     */
    public function withdrawalFromModal($id)
    {
        $withdrawal = Withdrawal::find($id);
        if (! empty($withdrawal)) {
            $suitpayment = SuitPayPayment::create([
                'withdrawal_id' => $withdrawal->id,
                'user_id'       => $withdrawal->user_id,
                'pix_key'       => $withdrawal->pix_key,
                'pix_type'      => $withdrawal->pix_type,
                'amount'        => $withdrawal->amount,
                'observation'   => 'Прямой вывод',
            ]);

            if ($suitpayment) {
                $parm = [
                    'pix_key'        => $withdrawal->pix_key,
                    'pix_type'       => $withdrawal->pix_type,
                    'amount'         => $withdrawal->amount,
                    'suitpayment_id' => $suitpayment->id,
                ];

                $resp = self::pixCashOut($parm);

                if ($resp) {
                    $withdrawal->update(['status' => 1]);
                    Notification::make()
                        ->title('Запрос на вывод')
                        ->body('Вывод средств успешно запрошен')
                        ->success()
                        ->send();

                    return back();
                } else {
                    Notification::make()
                        ->title('Ошибка при выводе')
                        ->body('Ошибка при запросе на вывод средств')
                        ->danger()
                        ->send();

                    return back();
                }
            }
        }
    }

    /**
     * Cancel Withdrawal
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelWithdrawalFromModal($id)
    {
        $withdrawal = Withdrawal::find($id);
        if (! empty($withdrawal)) {
            $wallet = Wallet::where('user_id', $withdrawal->user_id)
                ->where('currency', $withdrawal->currency)
                ->first();

            if (! empty($wallet)) {
                $wallet->increment('balance_withdrawal', $withdrawal->amount);
                $withdrawal->update(['status' => 2]);

                Notification::make()
                    ->title('Вывод отменён')
                    ->body('Вывод средств успешно отменён')
                    ->success()
                    ->send();

                return back();
            }
            return back();
        }
        return back();
    }
}
