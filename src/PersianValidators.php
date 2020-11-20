<?php

namespace Sadegh19b\LaravelPersianValidation;

/**
 * @author Sadegh Barzegar <sadegh19b@gmail.com>
 * @since Sep 18, 2019
 */
class PersianValidators
{

    /**
     * Validate persian alphabet and space.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validatePersianAlpha($attribute, $value, $parameters)
    {
        return preg_match("/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}\s]+$/u", $value);
    }

    /**
     * Validate persian number.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validatePersianNumber($attribute, $value, $parameters)
    {
        return preg_match('/^[\x{6F0}-\x{6F9}]+$/u', $value);
    }

    /**
     * Validate persian alphabet, number and space.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validatePersianAlphaNumber($attribute, $value, $parameters)
    {
        return preg_match('/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}\s]+$/u', $value);
    }


    /**
     * Validate persian alphabet, persian number, english number and space.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validatePersianAlphaEngNumber($attribute, $value, $parameters)
    {
        return preg_match('/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}\0-9\s]+$/u', $value);
    }

    /**
     * Validate string that is not contain persian alphabet and number.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validatePersianNotAccept($attribute, $value, $parameters)
    {
        if (is_string($value)) {
            return !preg_match("/[\x{600}-\x{6FF}]/u", $value);
        }

        return false;
    }

    /**
     * Validate iranian mobile number.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateIranianMobile($attribute, $value, $parameters)
    {
        return (preg_match('/^(((98)|(\+98)|(0098)|0)(9){1}[0-9]{9})+$/', $value) || preg_match('/^(9){1}[0-9]{9}+$/', $value))? true : false;
    }

    /**
     * Validate iran phone number.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateIranianPhone($attribute, $value, $parameters)
    {
        return preg_match('/^[2-9][0-9]{7}+$/', $value);
    }

    /**
     * Validate iran phone area code number.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateIranianPhoneAreaCode($attribute, $value, $parameters)
    {
        return preg_match('/^(0[1-9]{2})+$/', $value);
    }

    /**
     * Validate iran phone number with area code.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return boolean
     */
    public function validateIranianPhoneWithAreaCode($attribute, $value, $parameters)
    {
        return preg_match('/^(0[1-9]{2})[2-9][0-9]{7}+$/', $value) ;
    }

    /**
     * Validate Iran postal code format.
     * Old pattern /^(\d{5}-?\d{5})$/
     * New pattern ref: https://stackoverflow.com/questions/48719799/iranian-postal-code-validation
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateIranianPostalCode($attribute, $value, $parameters)
    {
        return preg_match("/\b(?!(\d)\1{3})[13-9]{4}[1346-9]-?[013-9]{5}\b/", $value);
    }

    /**
     * Validate iranian bank payment card number validation.
     * depending on 'http://www.aliarash.com/article/creditcart/credit-debit-cart.htm' article.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    function validateIranianBankCardNumber($attribute, $value, $parameters)
    {
        if (!preg_match('/^\d{16}$/', $value)) {
            return false;
        }

        $sum = 0;

        for ($position = 1; $position <= 16; $position++){
            $temp = $value[$position - 1];
            $temp = $position % 2 === 0 ? $temp : $temp * 2;
            $temp = $temp > 9 ? $temp - 9 : $temp;

            $sum += $temp;
        }

        return ($sum % 10 === 0);
    }

    /**
     * Validate iranian bank sheba number.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateIranianBankSheba($attribute, $value, $parameters)
    {
        $ibanReplaceValues = array();

        if (!empty($value))
        {
            $value = preg_replace('/[\W_]+/', '', strtoupper($value));

            if (( 4 > strlen($value) ||  strlen($value) > 34 ) || ( is_numeric($value [ 0 ])  || is_numeric($value [ 1 ]) ) || ( ! is_numeric($value [ 2 ]) || ! is_numeric($value [ 3 ]) )) {
                return false;
            }

            $ibanReplaceChars = range('A', 'Z');

            foreach (range(10, 35) as $tempvalue) {
                $ibanReplaceValues[] = strval($tempvalue);
            }

            $tmpIBAN = substr($value, 4) . substr($value, 0, 4);
            $tmpIBAN = str_replace($ibanReplaceChars, $ibanReplaceValues, $tmpIBAN);
            $tmpValue = intval(substr($tmpIBAN, 0, 1));

            for ($i = 1; $i < strlen($tmpIBAN); $i++) {
                $tmpValue *= 10;
                $tmpValue += intval(substr($tmpIBAN, $i, 1));
                $tmpValue %= 97;
            }

            if ($tmpValue != 1) {
                return false;
            }

            return true;
        }

        return false;
    }

   /**
    * Validate iranian national code number (Code Melli)
    *
    * @param $attribute
    * @param $value
    * @param $parameters
    * @return bool
    */
    public function validateIranianNationalCode($attribute, $value, $parameters)
    {
        if (!preg_match('/^\d{8,10}$/', $value) || preg_match('/^[0]{10}|[1]{10}|[2]{10}|[3]{10}|[4]{10}|[5]{10}|[6]{10}|[7]{10}|[8]{10}|[9]{10}$/', $value)) {
            return false;
        }

        $sub = 0;

        if (strlen($value) == 8) {
            $value = '00' . $value;
        } elseif (strlen($value) == 9) {
            $value = '0' . $value;
        }

        for ($i = 0; $i <= 8; $i++) {
            $sub = $sub + ( $value[$i] * ( 10 - $i ) );
        }

        if (( $sub % 11 ) < 2) {
            $control = ( $sub % 11 );
        } else {
            $control = 11 - ( $sub % 11 );
        }

        if ($value[9] == $control) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validate Url
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateCheckUrl($attribute, $value, $parameters)
    {
        return preg_match("/^(HTTP|http(s)?:\/\/(www\.)?[A-Za-z0-9]+([\-\.]{1,2}[A-Za-z0-9]+)*\.[A-Za-z]{2,40}(:[0-9]{1,40})?(\/.*)?)$/", $value);
    }

    /**
     * Validate Domain
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateCheckDomain($attribute, $value, $parameters)
    {
        return preg_match("/^((www\.)?(\*\.)?[A-Za-z0-9]+([\-\.]{1,2}[A-Za-z0-9]+)*\.[A-Za-z]{2,40}(:[0-9]{1,40})?(\/.*)?)$/", $value);
    }
}
