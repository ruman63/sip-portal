<?php

function currency ($number) {
    $pattern = '~(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?~';
    return preg_replace($pattern, "$1,", $number);
}
