<?php

use App\Enums\Common\Active;
use Carbon\Carbon;

if (!function_exists('activeOptions')) {
    /**
     * Retornar as opções de ativo.
     *
     * @return \Illuminate\Support\Collection;
     */
    function activeOptions()
    {
        return Active::all();
    }
}

if (!function_exists('carbon')) {
    /**
     * Retornar instância de data.
     *
     * @param mixed $date
     * @return Carbon\Carbon
     */
    function carbon($date = null)
    {
        if (!empty($date)) {
            if ($date instanceof DateTime) {
                return Carbon::instance($date);
            }

            return Carbon::parse(date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $date))));
        }

        return Carbon::now();
    }
}

if (!function_exists('ukDate')) {
    function ukDate($datetime = null, $timestamp = false)
    {
        $datetime = $datetime ? $datetime : Carbon::now();
        $format = $timestamp ? 'd/m/Y H:i' : 'd/m/Y';
        $timestamp = $timestamp ? 'Y-m-d H:i:s' : 'Y-m-d';
        return Carbon::createFromFormat($format, $datetime)->format($timestamp);
    }
}

if (!function_exists('brDate')) {
    /**
     * Converte data para formato brasileiro.
     *
     * @param mixed $date
     * @param mixed $timestamp
     * @return Carbon\Carbon
     */
    function brDate($datetime = null, $timestamp = false)
    {
        $datetime = $datetime ? $datetime : Carbon::now();
        $timestamp = $timestamp ? 'd/m/Y H:i' : 'd/m/Y';
        return Carbon::parse($datetime)->format($timestamp);
    }
}

if (!function_exists('floatToMoney')) {
    /**
     * Transforma float do banco em reais.
     *
     * @param string $str
     * @return string
     */
    function floatToMoney(float $value)
    {
        return number_format($value, 2, ',', '.');
    }
}

if (!function_exists('moneyToFloat')) {
    /**
     * Converte reais para float.
     *
     * @param string $str
     * @return string
     */
    function moneyToFloat(string $value)
    {
        if (!$value) return 0;

        $source = array('.', ',');
        $replace = array('', '.');
        return str_replace($source, $replace, $value);
    }
}

if (!function_exists('removeMask')) {
    /**
     * Remover máscara.
     *
     * @param string $str
     * @return string
     */
    function removeMask($str)
    {
        if (!$str) {
            return $str;
        }

        return preg_replace('/[^A-Za-z0-9]/', '', $str);
    }
}

if (!function_exists('insertMask')) {
    /**
     * Inserir máscara.
     *
     * @param string $str
     * @param string $mask
     * @return string
     */
    function insertMask(string $str, string $mask)
    {
        $str = str_replace(" ", "", $str);

        for ($i = 0; $i < strlen($str); $i++) {
            $mask[strpos($mask, "#")] = $str[$i];
        }

        return $mask;
    }
}

if (!function_exists('nifMask')) {
    /**
     * Inserir máscara.
     *
     * @param string $str
     * @return string
     */
    function nifMask(string $str)
    {
        if (strlen($str) == 11) {
            $mask = '###.###.###-##';
        } else {
            $mask = '##.###.###/####-##';
        }

        $str = str_replace(" ", "", $str);

        for ($i = 0; $i < strlen($str); $i++) {
            $mask[strpos($mask, "#")] = $str[$i];
        }

        return $mask;
    }
}

if (!function_exists('phoneMask')) {
    /**
     * Inserir máscara.
     *
     * @param string $str
     * @return string
     */
    function phoneMask(string $str)
    {
        $mask = '(##) #####-####';

        $str = str_replace(" ", "", $str);

        for ($i = 0; $i < strlen($str); $i++) {
            $mask[strpos($mask, "#")] = $str[$i];
        }

        return $mask;
    }
}

if (!function_exists('zipCodeMask')) {
    /**
     * Inserir máscara.
     *
     * @param string $str
     * @return string
     */
    function zipCodeMask(string $str)
    {
        $mask = '#####-###';

        $str = str_replace(" ", "", $str);

        for ($i = 0; $i < strlen($str); $i++) {
            $mask[strpos($mask, "#")] = $str[$i];
        }

        return $mask;
    }
}
