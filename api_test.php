<?php
echo "<PRE>";
function jsonapi($data)
{ 
    $postdata = http_build_query(
        array(
            json_encode($data),
        )
    );  
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context  = stream_context_create($opts);
    $result = file_get_contents('http://172.16.101.7/uhpapi/', null, $context);
    echo "----------------------------RESULT ------------------------\n";
    echo $result;
    echo "\n----------------------------RESULT  END------------------------\n";
    $response = json_decode( $result, true);
    return $response;
}


$data = array(
    'op' => 'rip_get',
    'host' => '172.16.101.66',
);
$r =  jsonapi($data);

print_r($r);
/*
$r['reply']['name'] = "API TEST"; // change name
$r['reply']['location']['latdeg'] = 33;

print_r($r['reply']);
$data = array(
    'op' => 'siteset',
    'host' => '172.16.101.66',
    'data' => $r['reply'],
);

//$r =  jsonapi($data);

echo "----------- Result -------------- \n";
print_r($r);
*/
?>