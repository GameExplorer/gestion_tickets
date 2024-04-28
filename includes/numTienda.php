<?php
if (!function_exists('getRealIP')) {
    function getRealIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        return $_SERVER['REMOTE_ADDR'];
    }
}

function posicion($direccion)
{
    if (substr($direccion, 0, 8) == '192.168.') {
        $lugar = substr($direccion, 8);
        $pos = strpos($lugar, '.');
        $lugar = substr($lugar, 0, $pos);
        return $lugar;
    } else {
        return false;
    }
}

$ip = getRealIP();

$almacen = ['0' => '01', '1' => '02', '2' => '03', '3' => '04', '4' => '05', '5' => '06', '6' => '07', '7' => '08', '8' => '12', '9' => '09', '10' => '14', '12' => '17', '13' => '51', '14' => '13', '15' => '52', '16' => '56', '17' => '57', '18' => '18', '20' => '15', '21' => '16'];
$virtual = ['0' => 'CT', '1' => 'LP', '2' => 'SR', '3' => 'TF', '4' => 'AM', '5' => 'PR', '6' => 'PC', '7' => 'LZ', '8' => 'CR', '9' => 'LL', '10' => 'MJ', '12' => 'GA', '13' => 'ML', '14' => 'VC', '15' => 'LE', '16' => 'TR', '17' => 'TE', '18' => 'PA', '20' => 'AR', '21' => 'JN'];
$sede = [
    '0' => 'Almacén Central',
    '1' => 'T02 - Las Palmas',
    '2' => 'T03 - S. Fernando',
    '3' => 'T04 - S/C Tenerife',
    '4' => 'T05 - Americas',
    '5' => 'T06 - Pto. Rosario',
    '6' => 'T07 - Pto. Cruz',
    '7' => 'T08 - Lanzarote',
    '8' => 'T12 - Cristianos',
    '9' => 'T09 - La Laguna',
    '10' => 'T14 - Morro Jable',
    '12' => 'T17 - Galdar',
    '13' => 'T51 - M. Larache',
    '14' => 'T13 - Vecindario',
    '15' => 'T52 - LEOS',
    '16' => 'T56 - Travieso',
    '17' => 'T57 - Telde',
    '18' => 'T18 - La Palma',
    '20' => 'T15 - Arguineguín',
    '21' => 'T16 - Jinamar'
];

$n = posicion($ip);

// Control for tienda 16: the GW is 92.168.0.248
if ($n == 0 && substr($ip, 10, 3) == '248') {
    $n = 21;
}

$tienda = $almacen[$n];
$vstore = $virtual[$n];
$nombre = $sede[$n];
?>