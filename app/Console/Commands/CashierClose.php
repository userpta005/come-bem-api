<?php

namespace App\Console\Commands;

use App\Models\Cashier;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CashierClose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashier:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fecha caixa automaticamente';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::beginTransaction();

        foreach (Cashier::query()->lazy() as $cashier) {
            $cashier->status = 2;
            $cashier->open_cashier_id = null;
            $cashier->user = null;
            $cashier->save();
        }

        DB::commit();
    }
}
