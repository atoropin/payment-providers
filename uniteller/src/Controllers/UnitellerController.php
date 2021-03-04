<?php

namespace Rir\PaymentProviders\Uniteller\Controllers;

use Rir\PaymentProviders\Uniteller\Events\UnitellerCallbackEvent;
use Rir\PaymentProviders\Uniteller\Facades\UnitellerFacade as Uniteller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UnitellerController extends Controller
{
    public function callback(Request $request)
    {
        $requestPayload = $request->all();

        if (!Uniteller::verify($requestPayload)) {
            Log::error('Uniteller callback: Invalid signature');
        }

        event(new UnitellerCallbackEvent($requestPayload));
    }

    public function success(Request $request) {}

    public function failed(Request $request) {}
}
