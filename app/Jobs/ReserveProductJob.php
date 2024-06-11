<?php

namespace App\Jobs;

use App\DTO\OrderDTO;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReserveProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderDTO;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OrderDTO $orderDTO)
    {
        $this->orderDTO = $orderDTO;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product = Product::findOrFail($this->orderDTO->product_id);

        if ($product->quantity < $this->orderDTO->quantity) {
            // Opcjonalnie: obsłuż brak dostępności produktów
            return;
        }

        \DB::transaction(function () use ($product) {
            $product->quantity -= $this->orderDTO->quantity;
            $product->save();

            Order::create([
                'product_id' => $product->id,
                'quantity' => $this->orderDTO->quantity,
            ]);
        });
    }
}
