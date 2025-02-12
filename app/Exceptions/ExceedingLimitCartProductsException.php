<?php

namespace App\Exceptions;

use App\Enums\LimitProductsInCart;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceedingLimitCartProductsException extends HttpException
{
    protected $message = "Exceeding limit cart products: "
        . LimitProductsInCart::Pizza->value . " pizzas and "
        . LimitProductsInCart::Drink->value . " drinks";

    public function __construct($message = null)
    {
        parent::__construct(400, $message ?? $this->message);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getStatusCode());
    }
}
