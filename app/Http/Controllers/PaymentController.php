<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Validator;
use App\Models\UserSubscription;
use Carbon\Carbon;

class PaymentController extends BaseController
{
    protected $payPalService;

    // Constantes para mejorar la legibilidad y mantenimiento
    const QUANTITY = '1';

    public function __construct(PayPalService $payPalService)
    {
        $this->payPalService = $payPalService;
    }

    public function createPayment(Request $request)
    {
        // Validar los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'plan_name' => 'required|string',
            'plan_price' => 'required|numeric',
            'currency' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // Crear el payload para PayPal
        $data = $this->createPaymentPayload($request);

        // Llama al servicio de PayPal para crear el pago
        $response = $this->payPalService->createPayment($data);

        // Verificar si la respuesta fue exitosa
        if ($response['success']) {
            return $this->sendResponse($response['data'], 'Payment created successfully.', 200);
        }

        // Si no fue exitoso, devolver error
        return $this->sendError('Payment creation failed.', $response['message'], 400);
    }

    // Método separado para crear el payload de pago
    private function createPaymentPayload(Request $request)
    {
        return [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $request->currency,
                        'value' => $request->plan_price,
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => $request->currency,
                                'value' => $request->plan_price,
                            ],
                        ],
                    ],
                    'items' => [
                        [
                            'name' => $request->plan_name,
                            'description' => 'Subscription Plan', // Descripción del plan
                            'unit_amount' => [
                                'currency_code' => $request->currency,
                                'value' => $request->plan_price,
                            ],
                            'quantity' => self::QUANTITY,
                        ],
                    ],
                ],
            ],
        ];
    }
}
