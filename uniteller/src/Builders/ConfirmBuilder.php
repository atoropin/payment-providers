<?php

namespace Rir\PaymentProviders\Uniteller\Builders;

use Illuminate\Contracts\Support\Arrayable;

class ConfirmBuilder implements Arrayable
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

    public function getBillNumber(): int
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

    public function toArray()
    {
        return [
            'Billnumber' => $this->getBillNumber(),
            'Shop_ID'    => $this->getShopId(),
            'Login'      => $this->getLogin(),
            'Password'   => $this->getPassword()
        ];
    }
}
