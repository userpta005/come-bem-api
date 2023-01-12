<?php

namespace App\Http\Controllers\API;

use App\Enums\AccountEntryType;
use App\Enums\CreditPurchaseStatus;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountEntry;
use App\Models\CreditPurchase;
use App\Models\Store;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationPagseguroController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        Log::info("NOTIFICACAO: ".json_encode($request->all()));
        $code = $request->input('notificationCode');
        $type = $request->input('notificationType');


        if (empty($code) || empty($type)) {
            return;
        }

        $isSandbox = boolval(config('laravel-pagseguro.use-sandbox'));

        $url = config('laravel-pagseguro.notificacation.production');

        if ($isSandbox) {
            $url = config('laravel-pagseguro.notificacation.sandbox');
        }

        $store = Store::with('tenant')->find($id);

        if (!$store) {
            return;
        }

        $credentials = "email={$store->tenant->pagseguro_email}&token={$store->tenant->pagseguro_token}";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-version' => '4.0'
        ])->get(
            "$url/{$code}?{$credentials}",
        );

        if ($response->failed()) {
            throw new Exception($response->body());
        }

        $xml = simplexml_load_string($response->body(), "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $creditPurchase = CreditPurchase::where('uuid', $array['reference'])->first();
        DB::beginTransaction();
        $inputs = [];
        if ($creditPurchase) {
            $inputs['status'] = CreditPurchaseStatus::PENDING;

            if ($array['status'] == 3) {
                $inputs['status'] = CreditPurchaseStatus::PAYED;

                $data = [];
                $data['account_id'] = $creditPurchase->account_id;
                $data['amount'] = $creditPurchase->amount;
                $data['description'] = AccountEntryType::CREDIT->name();
                $data['credit_purchase_id'] = $creditPurchase->id;
                $data['type'] = AccountEntryType::CREDIT;

                AccountEntry::query()->create($data);

                Account::where('id', $creditPurchase->account_id)->increment('balance', $creditPurchase->amount);
            }

            if ($array['status'] == 7) {
                $inputs['status'] = CreditPurchaseStatus::CANCELED;
            }

            $creditPurchase->fill($inputs)->save();
        }
        DB::commit();
    }
}
