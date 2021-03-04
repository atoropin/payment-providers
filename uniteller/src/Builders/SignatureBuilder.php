<?php

namespace Rir\PaymentProviders\Uniteller\Builders;

use Illuminate\Contracts\Support\Arrayable;

class SignatureBuilder implements Arrayable
{
    protected ?string $shopIdp = null;

    protected ?string $orderIdp = null;

    protected ?string $subtotalP = null;

    protected ?string $meanType = null;

    protected ?string $eMoneyType = null;

    protected ?string $lifeTime = null;

    protected ?string $customerIdp = null;

    protected ?string $cardIdp = null;

    protected ?string $iData = null;

    protected ?string $ptCode = null;

    protected ?string $orderLifetime = null;

    protected ?string $phoneVerified = null;

    protected ?string $password = null;

    public function setShopIdp(?string $shopIdp): object
    {
        $this->shopIdp = $shopIdp;

        return $this;
    }

    public function setOrderIdp(?string $orderIdp): object
    {
        $this->orderIdp = $orderIdp;

        return $this;
    }

    public function setSubtotalP(?string $subtotalP): object
    {
        $this->subtotalP = $subtotalP;

        return $this;
    }

    public function setMeanType(?string $meanType): object
    {
        $this->meanType = $meanType;

        return $this;
    }

    public function setEMoneyType(?string $eMoneyType): object
    {
        $this->eMoneyType = $eMoneyType;

        return $this;
    }

    public function setLifeTime(?string $lifeTime): object
    {
        $this->lifeTime = $lifeTime;

        return $this;
    }

    public function setCustomerIdp(?string $customerIdp): object
    {
        $this->customerIdp = $customerIdp;

        return $this;
    }

    public function setCardIdp(?string $cardIdp): object
    {
        $this->cardIdp = $cardIdp;

        return $this;
    }

    public function setIData(?string $iData): object
    {
        $this->iData = $iData;

        return $this;
    }

    public function setPtCode(?string $ptCode): object
    {
        $this->ptCode = $ptCode;

        return $this;
    }

    public function setOrderLifetime(?string $orderLifetime): object
    {
        $this->orderLifetime = $orderLifetime;

        return $this;
    }

    public function setPhoneVerified(?string $phoneVerified): object
    {
        $this->phoneVerified = $phoneVerified;

        return $this;
    }

    public function setPassword(?string $password): object
    {
        $this->password = $password;

        return $this;
    }

    public function getShopIdp(): ?string
    {
        return $this->shopIdp;
    }

    public function getOrderIdp(): ?string
    {
        return $this->orderIdp;
    }

    public function getSubtotalP(): ?string
    {
        return $this->subtotalP;
    }

    public function getMeanType(): ?string
    {
        return $this->meanType;
    }

    public function getEMoneyType(): ?string
    {
        return $this->eMoneyType;
    }

    public function getLifeTime(): ?string
    {
        return $this->lifeTime;
    }

    public function getCustomerIdp(): ?string
    {
        return $this->customerIdp;
    }

    public function getCardIdp(): ?string
    {
        return $this->cardIdp;
    }

    public function getIData(): ?string
    {
        return $this->iData;
    }

    public function getPtCode(): ?string
    {
        return $this->ptCode;
    }

    public function getOrderLifetime(): ?string
    {
        return $this->orderLifetime;
    }

    public function getPhoneVerified(): ?string
    {
        return $this->phoneVerified;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function toArray(): array
    {
        return [
            'Shop_IDP'      => $this->getShopIdp(),
            'Order_IDP'     => $this->getOrderIdp(),
            'Subtotal_P'    => $this->getSubtotalP(),
            'MeanType'      => $this->getMeanType(),
            'EMoneyType'    => $this->getEMoneyType(),
            'Lifetime'      => $this->getLifeTime(),
            'Customer_IDP'  => $this->getCustomerIdp(),
            'Card_IDP'      => $this->getCardIdp(),
            'IData'         => $this->getIData(),
            'PT_Code'       => $this->getPtCode(),
            'Password'      => $this->getPassword()
        ];
    }

    /**
     * Create signature
     */
    public function create(): string
    {
        $string = join('&', array_map(function ($item) {
            return md5($item);
        }, $this->toArray()));

        return strtoupper(md5($string));
    }

    /**
     * Verify signature
     *
     * @param string $signature
     * @return bool
     */
    public function verify(string $signature): bool
    {
        return $this->create() === $signature;
    }
}
