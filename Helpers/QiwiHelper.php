<?php
/**
 * Created by PhpStorm.
 * User: 38095
 * Date: 05.03.2019
 * Time: 15:32
 */

namespace Dimakozachkov\QiwiSimplePackage\Helpers;


class QiwiHelper implements QiwiHelperInterface
{
    private $curl;
    
    /**
     * @param float $amount
     * @param string $currency
     * @param string $phone
     *
     * @return mixed
     */
    public function send(float $amount, string $currency, string $phone): string
    {
        $url = $this->getUrl();
        $token = $this->getToken();
        $header = $this->getHeader($token);
        $body = $this->getBody($amount, $currency, $phone);
        $opt = $this->getOpt($url, $header, $body);

        $this->init($opt);
        $result = $this->execute();

        return $result;
    }

    /**
     * @return string
     */
    private function getUrl(): string
    {
        return "https://edge.qiwi.com/sinap/api/v2/terms/99/payments";
    }

    /**
     * @return string
     */
    private function getToken(): string
    {
        return config('qiwi.token');
    }

    /**
     * @param string $token
     *
     * @return array
     */
    private function getHeader(string $token): array
    {
        return [
            'Accept: application/json',
            'Content-type: application/json',
            "Authorization: Bearer {$token}",
        ];
    }

    /**
     * @param string $url
     * @param array $header
     * @param string $body
     *
     * @return array
     */
    private function getOpt(string $url, array $header, string $body): array
    {
        return [
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $body,
        ];
    }

    /**
     * @param float $amount
     * @param string $currency
     * @param string $phone
     * @param string $comment
     *
     * @return string
     */
    private function getBody(float $amount, string $currency, string $phone, string $comment = ''): string
    {
        return collect([
            'id' => (1000 * time()) . "",
            'sum' => [
                'amount' => $amount,
                'currency' => $currency
            ],
            'paymentMethod' => [
                'type' => 'Account',
                'accountId' => $currency
            ],
            'fields' => [
                'account' => $phone,
            ],
            'comment' => $comment
        ])->toJson();
    }

    /**
     * @param array $opt
     *
     * @return void
     */
    private function init(array $opt): void
    {
        $this->curl = curl_init();
        curl_setopt_array($this->curl, $opt);
    }

    /**
     * @return string
     */
    private function execute(): string
    {
        ob_start();
        curl_exec($this->curl);
        curl_close($this->curl);
        $result = ob_get_contents();
        ob_clean();

        return $result;
    }

}