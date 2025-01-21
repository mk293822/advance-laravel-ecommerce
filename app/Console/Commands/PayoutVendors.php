<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\Payout;
use App\Models\Vendor;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PayoutVendors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payout:vendors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform vendors payout on the 1st of each month!';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting monthly payout process for vendors...');

        $vendors = Vendor::eligibleForPayout()->get();

        foreach ($vendors as $vendor) {
          $this->processPayout($vendor);
        }

        $this->info('Monthly payout process completed!');

        return Command::SUCCESS;

    }

    protected function processPayout(Vendor $vendor)
    {
      $this->info('Processing payout for vendor [ID='.$vendor->user_id.'] - "'.$vendor->store_name.'"');

      try{
        DB::beginTransaction();
        $startingFrom = Payout::where('vendor_id', $vendor->user_id)
          ->orderBy('until', 'desc')
          ->value('until');

        $startingFrom = $startingFrom ?: Carbon::make('1970-01-01');

        $until = Carbon::now()->subMonthNoOverflow()->startOfMonth();

//        dd($until);
        $vendor_subtotal = Order::query()
          ->where('vendor_user_id', $vendor->user_id)
          ->where('status', OrderStatusEnum::Paid->value)
          ->whereBetween('created_at', [$startingFrom, $until])
          ->sum('vendor_subtotal');

        if($vendor_subtotal){
          $this->info('Payout made with amount: '.$vendor_subtotal);
          Payout::create([
            'vendor_id'=>$vendor->user_id,
            'amount'=>$vendor_subtotal,
            'starting_from'=>$startingFrom,
            'until'=>$until,
          ]);
          $vendor->user->transfer((int)($vendor_subtotal * 100), config('app.currency'));

        } else {
          $this->info('Nothing to process!');
        }
        DB::commit();

      } catch (\Exception $e){
        DB::rollBack();
        $this->error($e->getMessage());
      }

    }
}
