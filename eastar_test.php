<?php

$start = microtime(true);
require "eastar.php";

$time_start = microtime(true); 



function routingTest($uhp)
{    
    $uhp->routingTableClean();
    // ------------------------------------------ STATIC ROUTE 
    echo "Testing 'Add static route'";
    $new['vlan']  = 33;
    $new['ipaddr'] = "171.16.6.0";
    $new['mask']   = "255.255.255.0";
    $new['gwip']   = "172.16.101.1";
    $new['title']  = "title";
    $new['type']   = "Static route";
    $uhp->routingApply($new, $uhp::UHP_ON_NEW);
    $r = $uhp->routingTableGet();
    $test = false;
    foreach($r as $key => $route)
    {
        if ($route['type'] == "Static route" && $route['vlan'] == $new['vlan'] && $route['ipaddr'] == $new['ipaddr'] && $route['title'] == $new['title'])
        {
            $test = true;            
            $rkey = $key;
        } 
    }
    if ($test)
        echo ".....OK\n";
    else
    {
        echo ".....FAIL\n";
        return;
    }

    echo "Testing 'Update Static routing'";
    $r = $uhp->routingTableGet();
    $r[$rkey]['title'] = "new";
    $r[$rkey]['ipaddr'] = "171.16.7.0";
    $uhp->routingApply($r[$rkey]);
    $rc = $uhp->routingTableGet();

    $test = false;
    if ($r[$rkey]['title'] ==  $rc[$rkey]['title'] && $r[$rkey]['ipaddr'] == $rc[$rkey]['ipaddr'])
        $test = true;

    if ($test)
        echo ".....OK\n";
    else
    {
        echo ".....FAIL\n";
        return;
    }

    echo "Testing 'Delete Static routing'";
    $test = false;

    $uhp->routingApply($r[$rkey], $uhp::UHP_ON_DELETE);
    $r = $uhp->routingTableGet();
    if (isset($r[$rkey]) && $r[$rkey]['vlan'] == 33 && $rc[$rkey]['title'] == "new" )
    {
        $test = false;
    } else
        $test = true;
        
    if ($test)
        echo ".....OK\n";
    else
    {
        echo ".....FAIL\n";
        return;
    }
    // --------------------------------------------------------------- ADD IP ADDRESS
//    print_r($r);
    unset($new);
    $new['vlan'] = 44;
    $new['ipaddr'] = "4.3.2.0";
    $new['mask'] =  "255.255.255.0";
    $new['localaccess'] = true;
    $new['title'] = "ipaddr";
    $new['type'] = "IP Address";

    echo "Testing 'Add IP address'";
    $uhp->routingApply($new,  $uhp::UHP_ON_NEW);

    $r = $uhp->routingTableGet();
    $test = false;
    foreach($r as $key => $route)
    {
        if ($route['type'] == "IP Address" && $route['vlan'] == $new['vlan'] && $route['ipaddr'] == $new['ipaddr'] && $route['title'] == $new['title'])
        {
            $test = true;            
            $rkey = $key;
        } 
    }
    if ($test)
        echo ".....OK\n";
    else
    {
        echo ".....FAIL\n";
        return;
    }


    echo "Testing 'Update IP address'";
    $r = $uhp->routingTableGet();
    $r[$rkey]['title'] = "new";
    $r[$rkey]['ipaddr'] = "171.16.7.0";
    $uhp->routingApply($r[$rkey]);
    $rc = $uhp->routingTableGet();

    $test = false;
    if ($r[$rkey]['title'] ==  $rc[$rkey]['title'] && $r[$rkey]['ipaddr'] == $rc[$rkey]['ipaddr'])
        $test = true;

    if ($test)
        echo ".....OK\n";
    else
    {
        echo ".....FAIL\n";
        return;
    }

    echo "Testing 'Delete IP address'";
    $test = false;

    $uhp->routingApply($r[$rkey], $uhp::UHP_ON_DELETE);
    $r = $uhp->routingTableGet();
    if (isset($r[$rkey]) && $r[$rkey]['vlan'] == 33 && $rc[$rkey]['title'] == "new" )
    {
        $test = false;
    } else
        $test = true;
        
    if ($test)
        echo ".....OK\n";
    else
    {
        echo ".....FAIL\n";
        return;
    }
}


function profileTest($uhp)
{
    $p = $uhp->profileLoad(1);
//    print_r($p);
}



//$uhp = new UHP("172.16.101.66");
//$hub = new UHP("10.0.0.150");
//$hub->SetProfile("uhp1000_3_4_2_7");

//$uhp = new UHP("172.16.101.66");
//$uhp->SetProfile("uhp1000_3_4_2_7");


//$uhp->hello();
//print_r($uhp->ipaddrRules());
//$uhp->debug(true);
//$hub->debug(true);



//$site_hub = $hub->siteLoad();
//$uhp->siteApply($site_hub);

//$p = $hub->profileList(true);
//$uhp->profilesApply($p);

//$s = $hub->stationsGet();
//$uhp->stationsGet();


//$table = $hub->stationsTableSizeGet();
//print_r($table);
//$uhp->stationsTableSizeApply($table);


//$stations = $hub->stationsGet();
//print_r($stations);
//$uhp->stationsApply($stations);



//$hr = $hub->routingTableGet();
//print_r($hr);

//$uhp->ignoreIP(array("172.16.101.66", "10.0.0.150"));
//$uhp->routingTableApply($hr);



//routingTest($uhp);

//$dump = $uhp->routingTableGet();

//$dump = $uhp_hub->dump();
//file_put_contents("dump.json", json_encode($dump));
//print_r($dump);


//$uhp->UHPCommand(UHP::UHP_CMD_REBOOT);


//$rip = $uhp->ripGet();
//print_r($rip);

//$log = $uhp->log();
//print_r($log);

//routingTest($uhp);
//profileTest($uhp);

// $hub->getDemodulator1();
// $hub->getModulator();

$uhp = new UHP("11.0.0.2");
print_r($uhp->overview());



//execution time of the script
echo "Process took ". (number_format(microtime(true) - $start, 2)*1000). " ms.\n";

?>
