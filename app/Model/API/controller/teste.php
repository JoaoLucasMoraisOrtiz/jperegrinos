<?php
/* $payment = date('d/m/y', strtotime('last year')); */

$lastPayment = strtotime('07-11-2021');

if($lastPayment > strtotime('-1 month')){
    echo "tudo certo";
}else{
    echo 'xiiiii';
}