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

        $creditPurchase = CreditPurchase::where('uuid', $request->reference_id)->first();
        DB::beginTransaction();
        $inputs = [];
        if ($creditPurchase) {
            $inputs['status'] = CreditPurchaseStatus::PENDING;

            if ($request->charges[0]->status == "PAID") {
                $inputs['status'] = CreditPurchaseStatus::PAYED;

                $data = [];
                $data['account_id'] = $creditPurchase->account_id;
                $data['amount'] = $creditPurchase->amount;
                $data['description'] = AccountEntryType::CREDIT->name();
                $data['credit_purchase_id'] = $creditPurchase->id;
                $data['type'] = AccountEntryType::CREDIT;

                AccountEntry::query()->create($data);

                Account::where('id', $creditPurchase->account_id)->increment('balance', $creditPurchase->amount);
            } elseif ($request->charges[0]->status == "CANCELED") {
                $inputs['status'] = CreditPurchaseStatus::CANCELED;
            }

            $creditPurchase->fill($inputs)->save();
        }
        DB::commit();
    }
}
