<?php
/**
 * Created by PhpStorm.
 * User: 38095
 * Date: 05.03.2019
 * Time: 15:32
 */

namespace Dimakozachkov\QiwiSimplePackage\Helpers;


interface QiwiHelperInterface
{
    /**
     * @param float $amount
     * @param string $currency
     * @param string $phone
     *
     * @return string
     */
    public function send(float $amount, string $currency, string $phone): string;

}