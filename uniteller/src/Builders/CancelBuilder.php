<?php

namespace Rir\PaymentProviders\Uniteller\Builders;

use Illuminate\Contracts\Support\Arrayable;

class CancelBuilder implements Arrayable
{
    /**
     * Номер платежа в системе Uniteller (RRN).
     */
    protected int $billNumber;

    /**
     * Идентификатор точки продажи в системе Uniteller.
     */
    protected string $shopId;

    /**
     * Логин.
     */
    protected string $login;

    /**
     * Пароль.
     */
    protected string $password;

    /**
     * Сумма возврата средств. Должна быть в диапазоне от 0.01 руб. до суммы платежа включительно.
     * В качестве десятичного разделителя используется точка.
     * (Если Subtotal_P не передаётся в запросе, то отмена платежа происходит на полную сумму)
     */
    protected ?float $subtotalP = null;

    /**
     * Код валюты отмены или возврата средств. Может быть использован только код валюты авторизации.
     */
    protected ?string $currency = null;

    /**
     * Причина отмена операции.
     * По умолчанию RVRReason::SHOP.
     */
    protected ?string $rvrReason = null;

    public function setBillNumber(int $billNumber): object
    {
        $this->billNumber = $billNumber;

        return $this;
    }

    public function setShopId(string $shopId): object
    {
        $this->shopId = $shopId;

        return $this;
    }

    public function setLogin(string $login): object
    {
        $this->login = $login;

        return $this;
    }

    public function setPassword(string $password): object
    {
        $this->password = $password;

        return $this;
    }

    public function setSubtotalP(?float $subtotalP): object
    {
        $this->subtotalP = $subtotalP;

        return $this;
    }

    public function setCurrency(?string $currency): object
    {
        $this->currency = $currency;

        return $this;
    }

    public function setRvrReason(?string $rvrReason): object
    {
        $this->rvrReason = $rvrReason;

        return $this;
    }

    public function getBillNumber(): ?int
    {
        return $this->billNumber;
    }

    public function getShopId(): string
    {
        return $this->shopId;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSubtotalP(): ?float
    {
        return $this->subtotalP;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getRvrReason(): ?int
    {
        return $this->rvrReason;
    }

    public function toArray()
    {
        return [
            'Billnumber' => $this->getBillNumber(),
            'Shop_ID'    => $this->getShopId(),
            'Login'      => $this->getLogin(),
            'Password'   => $this->getPassword(),
            'Subtotal_P' => $this->getSubtotalP(),
            'Currency'   => $this->getCurrency(),
            'RVRReason'  => $this->getRvrReason()
        ];
    }
}
