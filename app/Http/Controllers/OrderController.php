<?php

namespace App\Http\Controllers;

use App\DTO\OrderDTO;
use App\Http\Requests\ReserveProductRequest;
use App\Jobs\ReserveProductJob;

class OrderController extends Controller
{
    public function reserve(ReserveProductRequest $request)
    {
        $orderDTO = OrderDTO::fromRequest($request);

        ReserveProductJob::dispatch($orderDTO);

        return response()->json(['success' => 'Produkt zapisany.']);
    }
}
