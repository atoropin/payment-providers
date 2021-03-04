<?php

namespace Rir\PaymentProviders\Uniteller\Builders;

use Illuminate\Contracts\Support\Arrayable;

class SignatureVerifier implements Arrayable
{
    protected ?string $orderId = null;

    protected ?string $status = null;

    protected ?string $password = null;

    protected array $fields = [];

    public function setOrderId(?string $orderId): object
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function setStatus(?string $status): object
    {
        $this->status = $status;

        return $this;
    }

    public function setPassword(?string $password): object
    {
        $this->password = $password;

        return $this;
    }

    public function setFields(array $fields): object
    {
        $this->fields = $fields;

        return $this;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function toArray(): array
    {
        return [
                'Order_ID' => $this->getOrderId(),
                'Status'   => $this->getStatus(),
                'Password' => $this->getPassword()
            ] + $this->getFields();
    }

    /**
     * Create signature
     */
    public function create(): string
    {
        return strtoupper(md5(join('', $this->toArray())));
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
