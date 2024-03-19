<?php

function active_class($path, $active = 'active')
{
    return call_user_func_array('Request::is', (array) $path) ? $active : '';
}

function is_active_route($path)
{
    return call_user_func_array('Request::is', (array) $path) ? 'true' : 'false';
}

function show_class($path)
{
    return call_user_func_array('Request::is', (array) $path) ? 'show' : '';
}

function moneyFormatIndia($number)
{
    $number = round((float)$number, 2); // Ensure $number is a float
    // Windows is not supported by money_format
    if (setlocale(LC_MONETARY, 'en_IN')) {
        $decimal = '.';
        return number_format($number, 2, $decimal, ',');
    } else {
        if (floor($number) == $number) {
            $append = '.00';
        } else {
            $append = '';
        }
        $number = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $number);
        return $number . $append;
    }
}


function createDateFormat($date)
{
    return date("d/m/Y h:i:s A", strtotime($date->toDateTime()->format('r')));
}

function convert_number($number)
{
    if (($number < 0) || ($number > 999999999)) {
        throw new Exception("Number is out of range");
    }
    $giga = floor($number / 1000000);
    // Millions (giga)
    $number -= $giga * 1000000;
    $kilo = floor($number / 1000);
    // Thousands (kilo)
    $number -= $kilo * 1000;
    $hecto = floor($number / 100);
    // Hundreds (hecto)
    $number -= $hecto * 100;
    $deca = floor($number / 10);
    // Tens (deca)
    $n = $number % 10;
    // Ones
    $result = "";
    if ($giga) {
        $result .= $this->convert_number($giga) .  "Million";
    }
    if ($kilo) {
        $result .= (empty($result) ? "" : " ") . $this->convert_number($kilo) . " Thousand";
    }
    if ($hecto) {
        $result .= (empty($result) ? "" : " ") . $this->convert_number($hecto) . " Hundred";
    }
    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
    if ($deca || $n) {
        if (!empty($result)) {
            $result .= " and ";
        }
        if ($deca < 2) {
            $result .= $ones[$deca * 10 + $n];
        } else {
            $result .= $tens[$deca];
            if ($n) {
                $result .= "-" . $ones[$n];
            }
        }
    }
    if (empty($result)) {
        $result = "zero";
    }
    return $result;
}
if (!function_exists('formatMongoDate')) {
    /**
     * Convert MongoDB UTCDateTime to formatted date using Carbon.
     *
     * @param \MongoDB\BSON\UTCDateTime|null $utcDateTime
     * @param string $format
     * @return string|null
     */
    function formatMongoDate($utcDateTime, $format = 'Y-m-d')
    {
        if ($utcDateTime === null) {
            return null;
        }

        $dateTime = $utcDateTime->toDateTime();

        if ($dateTime === null) {
            return null;
        }

        $carbonDate = \Carbon\Carbon::createFromTimestamp($dateTime->getTimestamp());
        return $carbonDate->format($format);
    }
}

if (!function_exists('formatMongoFullDate')) {
    /**
     * Convert MongoDB UTCDateTime to formatted date using Carbon.
     *
     * @param \MongoDB\BSON\UTCDateTime $utcDateTime
     * @param string $format
     * @return string
     */
    function formatMongoFullDate($utcDateTime, $format = 'd-m-Y H:i:s')
    {
        if ($utcDateTime === null) {
            return null;
        }

        $dateTime = $utcDateTime->toDateTime();

        if ($dateTime === null) {
            return null;
        }

        $carbonDate = \Carbon\Carbon::createFromTimestamp($dateTime->getTimestamp());
        return $carbonDate->format($format);
    }
}
