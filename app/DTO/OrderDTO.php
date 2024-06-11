<?php

namespace App\DTO;

class OrderDTO
{
    public int $product_id;
    public int $quantity;

    public function __construct(int $product_id, int $quantity)
    {
        $this->product_id = $product_id;
        $this->quantity = $quantity;
    }

    public static function fromRequest($request): self
    {
        return new self(
            $request->input('product_id'),
            $request->input('quantity')
        );
    }
}
