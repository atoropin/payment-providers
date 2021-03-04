<?php

namespace Rir\PaymentProviders\Uniteller\Builders;

use Illuminate\Contracts\Support\Arrayable;

class PaymentBuilder implements Arrayable
{
    /**
     * Идентификатор точки продажи в системе Uniteller.
     * Формат: текст, содержащий либо латинские буквы и цифры в количестве от 1 до 64,
     * либо две группы латинских букв и цифр, разделенных «-»
     * (первая группа от 1 до 15 символов, вторая группа от 1 до 11 символов), к регистру нечувствителен.
     */
    protected ?string $shopIdp = null;

    /**
     * Номер заказа в системе расчётов интернет-магазина, соответствующий данному платежу.
     * Может быть любой непустой строкой максимальной длиной 127 символов, не может содержать только пробелы.
     * Значение Order_IDP должно быть уникальным для всех оплаченных заказов
     * (заказов, по которым успешно прошла блокировка средств) в рамках одного магазина (одной точки продажи).
     * Пока по заказу не проведена блокировка средств (авторизация),
     * допускается несколько запросов с одинаковым Order_IDP
     * (например, несколько попыток оплаты одного и того же заказа).
     * При использовании электронных валют номер заказа должен быть уникальным для каждого запроса на оплату.
     */
    protected ?string $orderIdp = null;

    /**
     * Сумма покупки в валюте, оговоренной в договоре с банком-эквайером.
     * В качестве десятичного разделителя используется точка,
     * не более 2 знаков после разделителя. Например, 12.34.
     */
    protected ?float $subtotalP = null;

    /**
     * Подпись, гарантирующая неизменность критичных данных оплаты (суммы, Order_IDP).
     */
    protected ?string $signature = null;

    /**
     * URL страницы, на которую должен вернуться Покупатель после успешного осуществления платежа в системе Uniteller.
     */
    protected ?string $urlReturnOk = null;

    /**
     * URL страницы, на которую должен вернуться Покупатель после неуспешного осуществления платежа в системе Uniteller.
     */
    protected ?string $urlReturnNo = null;

    /**
     * Валюта платежа. Параметр обязателен для точек продажи, работающих с валютой, отличной от российского рубля.
     * Для оплат в российских рублях параметр необязательный.
     * Возможные значения:
     * RUB — российский рубль;
     * EUR — евро;
     * USD — доллар США.
     */
    protected ?string $currency = null;

    /**
     * Адрес электронной почты. (64 символа)
     */
    protected ?string $email = null;

    /**
     * Время жизни формы оплаты в секундах, начиная с момента её показа. Должно быть целым положительным числом.
     * Если Покупатель использует форму дольше указанного времени, то форма оплаты будет считаться устаревшей,
     * и платёж не будет принят. Покупателю в этом случае будет предложено вернуться на сайт Мерчанта
     * для повторного выполнения заказа.
     * Значение параметра рекомендуется устанавливать не более 300 сек.
     */
    protected ?int $lifetime = null;

    /**
     * Время жизни (в секундах) заказа на оплату банковской картой, начиная с момента первого вывода формы оплаты.
     */
    protected ?int $orderLifetime = null;

    /**
     * Идентификатор Покупателя, используемый некоторыми интернет-магазинами. (64 символа)
     */
    protected ?string $customerIdp = null;

    /**
     * Идентификатор зарегистрированной карты. (до 128 символов)
     */
    protected ?string $cardIdp = null;

    /** */
    protected ?string $iData = null;

    /**
     * Тип платежа. Произвольная строка длиной до десяти символов включительно.
     * В подавляющем большинстве схем подключения интернет-магазинов этот параметр не используется.
     */
    protected ?string $ptCode = null;

    /**
     * Платёжная система кредитной карты.
     * Может принимать значения:
     * 0 — любая, 1 — VISA, 2 — MasterCard, 3 — Diners Club, 4 — JCB, 5 — American Express.
     */
    protected ?int $meanType = null;

    /**
     * Тип электронной валюты.
     * 0 - Любая система электронных платежей
     * 1 - Яндекс.Деньги
     * 13 - Оплата наличными (Евросеть, Яндекс.Деньги и пр.)
     * 18 - QIWI Кошелек REST (по протоколу REST)
     * 29 - WebMoney WMR
     */
    protected ?int $eMoneyType = null;

    /**
     * Срок жизни заказа оплаты в электронной платёжной системе в часах (от 1 до 1080 часов).
     * Значение параметра BillLifetime учитывается только для QIWI-платежей.
     */
    protected ?int $billLifetime = null;

    /**
     * Признак преавторизации платежа. При использовании в запросе должен принимать значение “1”.
     */
    protected ?bool $preauth = null;

    /**
     * Признак того, что платёж является «родительским» для последующих рекуррентных платежей.
     * Может принимать значение “1”.
     */
    protected ?bool $isRecurrentStart = null;

    /**
     * Список дополнительных полей, передаваемых в уведомлении об изменении статуса заказа.
     */
    protected ?string $callbackFields = null;

    /**
     * Запрашиваемый формат уведомления о статусе оплаты.
     * Если параметр имеет значение "json", то уведомление направляется в json-формате.
     * Во всех остальных случаях уведомление направляется в виде POST-запроса.
     */
    protected ?string $callbackFormat = null;

    /**
     * Код языка интерфейса платёжной страницы. Может быть en или ru. (2 символа)
     */
    protected ?string $language = null;

    /**
     * Комментарий к платежу. (до 1024 символов)
     */
    protected ?string $comment = null;

    /**
     * Имя Покупателя, переданное с сайта Мерчанта. (64 символа)
     */
    protected ?string $firstName = null;

    /**
     * Фамилия Покупателя, переданная с сайта Мерчанта. (64 символа)
     */
    protected ?string $lastName = null;

    /**
     * Отчество. (64 символа)
     */
    protected ?string $middleName = null;

    /**
     * Телефон. (64 символа)
     */
    protected ?string $phone = null;

    /**
     * Верифицированный мерчантом номер телефона. (64 символа)
     * Если передаётся, то:
     * – значение Phone устанавливается равным PhoneVerified;
     * – участвует в расчёте Signature
     */
    protected ?string $phoneVerified = null;

    /**
     * Адрес. (128 символов)
     */
    protected ?string $address = null;

    /**
     * Название страны Покупателя. (64 символа)
     */
    protected ?string $country = null;

    /**
     * Код штата/региона. (3 символа)
     */
    protected ?string $state = null;

    /**
     * Город. (64 символа)
     */
    protected ?string $city = null;

    /**
     * Почтовый индекс. (64 символа)
     */
    protected ?string $zip = null;

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

    public function setSubtotalP(?float $subtotalP): object
    {
        $this->subtotalP = $subtotalP;

        return $this;
    }

    public function setSignature(?string $signature): object
    {
        $this->signature = $signature;

        return $this;
    }

    public function setUrlReturnOk(?string $urlReturnOk): object
    {
        $this->urlReturnOk = $urlReturnOk;

        return $this;
    }

    public function setUrlReturnNo(?string $urlReturnNo): object
    {
        $this->urlReturnNo = $urlReturnNo;

        return $this;
    }

    public function setCurrency(?string $currency): object
    {
        $this->currency = $currency;

        return $this;
    }

    public function setEmail(?string $email): object
    {
        $this->email = $email;

        return $this;
    }

    public function setLifetime(?int $lifetime): object
    {
        $this->lifetime = $lifetime;

        return $this;
    }

    public function setOrderLifetime(?int $orderLifetime): object
    {
        $this->orderLifetime = $orderLifetime;

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

    public function setMeanType(?int $meanType): object
    {
        $this->meanType = $meanType;

        return $this;
    }

    public function setEMoneyType(?int $eMoneyType): object
    {
        $this->eMoneyType = $eMoneyType;

        return $this;
    }

    public function setBillLifetime(?int $billLifetime): object
    {
        $this->billLifetime = $billLifetime;

        return $this;
    }

    public function usePreauth(): object
    {
        $this->preauth = true;

        return $this;
    }

    public function useRecurrentPayment(): object
    {
        $this->isRecurrentStart = true;

        return $this;
    }

    public function setCallbackFields(array $callbackFields): object
    {
        $this->callbackFields = join(' ', $callbackFields);

        return $this;
    }

    public function setCallbackFormat(?string $callbackFormat): object
    {
        $this->callbackFormat = $callbackFormat;

        return $this;
    }

    public function setLanguage(?string $language): object
    {
        $this->language = $language;

        return $this;
    }

    public function setComment(?string $comment): object
    {
        $this->comment = $comment;

        return $this;
    }

    public function setFirstName(?string $firstName): object
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(?string $lastName): object
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setMiddleName(?string $middleName): object
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function setPhone(?string $phone): object
    {
        $this->phone = $phone;

        return $this;
    }

    public function setPhoneVerified(?string $phoneVerified): object
    {
        $this->phoneVerified = $phoneVerified;

        return $this;
    }

    public function setAddress(?string $address): object
    {
        $this->address = $address;

        return $this;
    }

    public function setCountry(?string $country): object
    {
        $this->country = $country;

        return $this;
    }

    public function setState(?string $state): object
    {
        $this->state = $state;

        return $this;
    }

    public function setCity(?string $city): object
    {
        $this->city = $city;

        return $this;
    }

    public function setZip(?string $zip): object
    {
        $this->zip = $zip;

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

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function getUrlReturnOk(): ?string
    {
        return $this->urlReturnOk;
    }

    public function getUrlReturnNo(): ?string
    {
        return $this->urlReturnNo;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getLifetime(): ?int
    {
        return $this->lifetime;
    }

    public function getOrderLifetime(): ?int
    {
        return $this->orderLifetime;
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

    public function getMeanType(): ?int
    {
        return $this->meanType;
    }

    public function getEMoneyType(): ?int
    {
        return $this->eMoneyType;
    }

    public function getBillLifetime(): ?int
    {
        return $this->billLifetime;
    }

    public function isPreauth(): ?bool
    {
        return $this->preauth;
    }

    public function isIsRecurrentStart(): ?bool
    {
        return $this->isRecurrentStart;
    }

    public function getCallbackFields(): ?string
    {
        return $this->callbackFields;
    }

    public function getCallbackFormat(): ?string
    {
        return $this->callbackFormat;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getPhoneVerified(): ?string
    {
        return $this->phoneVerified;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function toArray(): array
    {
        return [
            'Shop_IDP'         => $this->getShopIdp(),
            'Order_IDP'        => $this->getOrderIdp(),
            'Subtotal_P'       => $this->getSubtotalP(),
            'Signature'        => $this->getSignature(),
            'URL_RETURN_OK'    => $this->getUrlReturnOk(),
            'URL_RETURN_NO'    => $this->getUrlReturnNo(),
            'Currency'         => $this->getCurrency(),
            'Email'            => $this->getEmail(),
            'Lifetime'         => $this->getLifetime(),
            'OrderLifetime'    => $this->getOrderLifetime(),
            'Customer_IDP'     => $this->getCustomerIdp(),
            'Card_IDP'         => $this->getCardIdp(),
            'IData'            => $this->getIData(),
            'PT_Code'          => $this->getPtCode(),
            'MeanType'         => $this->getMeanType(),
            'EMoneyType'       => $this->getEMoneyType(),
            'BillLifetime'     => $this->getBillLifetime(),
            'Preauth'          => $this->isPreauth(),
            'IsRecurrentStart' => $this->isIsRecurrentStart(),
            'CallbackFields'   => $this->getCallbackFields(),
            'CallbackFormat'   => $this->getCallbackFormat(),
            'Language'         => $this->getLanguage(),
            'Comment'          => $this->getComment(),
            'FirstName'        => $this->getFirstName(),
            'LastName'         => $this->getLastName(),
            'MiddleName'       => $this->getMiddleName(),
            'Phone'            => $this->getPhone(),
            'PhoneVerified'    => $this->getPhoneVerified(),
            'Address'          => $this->getAddress(),
            'Country'          => $this->getCountry(),
            'State'            => $this->getState(),
            'City'             => $this->getCity(),
            'Zip'              => $this->getZip(),
        ];
    }
}
