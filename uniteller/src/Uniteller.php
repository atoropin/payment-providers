<?php

namespace Rir\PaymentProviders\Uniteller;

use Rir\PaymentProviders\Uniteller\Builders\CancelBuilder;
use Rir\PaymentProviders\Uniteller\Builders\ConfirmBuilder;
use Rir\PaymentProviders\Uniteller\Builders\PaymentBuilder;
use Rir\PaymentProviders\Uniteller\Builders\SignatureBuilder;
use Rir\PaymentProviders\Uniteller\Builders\SignatureVerifier;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Rir\PaymentProviders\Uniteller\Requests\CancelRequest;
use Rir\PaymentProviders\Uniteller\Requests\ConfirmRequest;

class Uniteller
{
    private string $shopId;

    private string $login;

    private string $password;

    private string $baseUrl;

    private string $successUrl;

    private string $failureUrl;

    /**
     * @param string $shopId
     * @param string $login
     * @param string $password
     * @param string $baseUrl
     * @param string $successUrl
     * @param string $failureUrl
     */
    public function __construct(
        string $shopId,
        string $login,
        string $password,
        string $baseUrl,
        string $successUrl,
        string $failureUrl
    ) {
        $this->shopId     = $shopId;
        $this->login      = $login;
        $this->password   = $password;
        $this->baseUrl    = $baseUrl;
        $this->successUrl = $successUrl;
        $this->failureUrl = $failureUrl;
    }

    public function process(PaymentBuilder $payment, $returnUrl = true)
    {
        if (!$payment->getShopIdp())
            $payment->setShopIdp($this->shopId);
        if (!$payment->getUrlReturnOk())
            $payment->setUrlReturnOk($this->successUrl);
        if (!$payment->getUrlReturnNo())
            $payment->setUrlReturnNo($this->failureUrl);

        $paymentUrl = $this->createPayment($payment);

        if ($returnUrl === true)
            return $paymentUrl;

        return Redirect::away($paymentUrl);
    }

    public function verify($requestPayload)
    {
        return $this->verifyCallbackRequest($requestPayload);
    }

    public function confirm(ConfirmBuilder $confirm)
    {
        $this->confirmPayment($confirm->toArray());
    }

    public function cancel(CancelBuilder $cancel)
    {
        $this->cancelPayment($cancel->toArray());
    }

    /**
     * @param $payment PaymentBuilder
     * @return string
     */
    private function createPayment($payment)
    {
        $signature = (new SignatureBuilder())
            ->setShopIdp($payment->getShopIdp())
            ->setOrderIdp($payment->getOrderIdp())
            ->setSubtotalP($payment->getSubtotalP())
            ->setMeanType($payment->getMeanType())
            ->setEMoneyType($payment->getEMoneyType())
            ->setLifeTime($payment->getLifetime())
            ->setCustomerIdp($payment->getCustomerIdp())
            ->setCardIdp($payment->getCardIdp())
            ->setIData($payment->getIData())
            ->setPtCode($payment->getPtCode())
            ->setPassword($this->password)
            ->create();

        $paymentParameters = $payment->setSignature($signature)->toArray();

        return $this->generateUrl($paymentParameters);
    }

    private function confirmPayment(array $confirmParameters)
    {
        return (new ConfirmRequest($this->baseUrl, $confirmParameters))->send();
    }

    private function cancelPayment(array $cancelParameters)
    {
        return (new CancelRequest($this->baseUrl, $cancelParameters))->send();
    }

    private function paymentResults() {}

    /**
     * @param array $paymentParameters
     * @return string
     */
    private function generateUrl(array $paymentParameters)
    {
        return sprintf('%s/pay?%s', $this->baseUrl, http_build_query($paymentParameters));
    }

    /**
     * Verify signature when Client will send callback request.
     *
     * @param array $callbackParameters
     * @return bool
     */
    private function verifyCallbackRequest(array $callbackParameters)
    {
        return (new SignatureVerifier())
            ->setOrderId(Arr::get($callbackParameters, 'Order_ID'))
            ->setStatus(Arr::get($callbackParameters, 'Status'))
            ->setFields(Arr::except($callbackParameters, ['Order_ID', 'Status', 'Signature']))
            ->setPassword($this->password)
            ->verify(Arr::get($callbackParameters, 'Signature'));
    }
}
