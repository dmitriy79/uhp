<?php

header("Content-Type: application/json; charset=UTF-8");
require "eastar.php";

$in = "";

function uhp_error($err = "unknown")
{
    global $in;
    $r['result'] = "error";
    $r['errorstr'] =  $err;
//    $r['in'] = $in;
    echo json_encode($r);
    die();
}

//print_r($_POST);
//if (!isset($_POST['uhp']))  uhp_error("wrong request");

//$in = json_decode($_POST['uhp'], true);
$in = json_decode(file_get_contents('php://input'), true);
//print_r($in);
//$in = json_decode($_POST[0], true);

if (!isset($in['op'])) uhp_error("no opcode");
if (!isset($in['host'])) uhp_error("no host");

$uhp = new UHP($in['host']);

try {
    switch($in['op'])
    {
        case "site_schema":
            $rules = $uhp->siteRules();
            $r['reply']  = $rules[key($rules)];
            break;
        case "site_get":
            $r['reply']  = $uhp->siteLoad();
            break;        
        case 'site_set':
            if (!isset($in['data'])) uhp_error("no data argument");
            $uhp->siteApply($in['data']);        
            break;
        case 'profiles_chema': // FIX-ME!!!!!!!
            $rules = $uhp->profileRules(); 
            $r['reply']  = $rules;
            break;
        case 'profile_get':   // load one profile ARGS: pid - number of profile
            if (!isset($in['pid'])) uhp_error("no pid argument");
            $r['reply']  = $uhp->profileLoad((int) $in['pid']);
            break;
        case 'profile_set':  // save one profile
            if (!isset($in['data'])) uhp_error("no data argument");
            $r['reply']  = $uhp->profileApply($in['data']);
            break;
        case 'profiles_get':
            $r['reply'] = $uhp->profileList();
            break;
        case 'profiles_set':
            if (!isset($in['data'])) uhp_error("no data argument");
            $r['reply'] = $uhp->profilesApply($in['data']);
            break;
        case 'profile_run':
            if (!isset($in['pid'])) uhp_error("no pid argument");   
            $r['reply'] = $uhp->profileRun($in['pid']);
            break;
        case 'station_schema': 
            $rules = $uhp->stationRules();
            $r['reply']  = $rules[key($rules)];
            break;
        case 'station_get':
            if (!isset($in['stid'])) uhp_error("no stid argument");   
            $r['reply'] = $uhp->stationGet((int) $in['stid']);
            break;
        case 'station_set':
            if (!isset($in['data'])) uhp_error("no data argument");
            $r['reply'] = $uhp->stationApply($in['data']);
            break;
        case 'stations_get':
            $r['reply'] =  $uhp->stationsGet();
            break;
        case 'stations_set':
            if (!isset($in['data'])) uhp_error("no data argument");
            $r['reply'] =  $uhp->stationsApply($in['data']);
            break;
        case 'stations_online':
            $r['reply']  = $uhp->stationsOnline();
            break;
        case 'stations_size_get':
            $r['reply']  = $uhp->stationsTableSizeGet();
            break;
        case 'stations_size_set':
            if (!isset($in['size'])) uhp_error("no size argument");
            $r['reply']  = $uhp->stationsTableSizeApply((int )$in['size']);
            break;
        case 'routingtable_clean':
            $r['reply'] = $uhp->routingTableClean();
            break;
        case 'routingtable_get':
            $r['reply'] = $uhp->routingTableGet();
            break;
        case 'routingtable_set':
            if (!isset($in['data'])) uhp_error("no data argument");
            $r['reply'] = $uhp->routingTableApply($in['data']);
            break;
        case 'routingschema_ipaddr':
            $r['reply'] = $uhp->ipaddrRules();
            break;
        case 'routingschema_static':
            $r['reply'] = $uhp->staticRouteRules();
            break;
        case 'routingschema_txmap':
            $r['reply'] = $uhp->txMapRules();
            break;
        case 'routingschema_vlanbr':
            $r['reply'] = $uhp->vlanBridgeRules();
            break;
        case 'routingschema_svlanrx':
            $r['reply'] = $uhp->svlanReceiveRules();
            break;
        case 'routing_set':
            if (!isset($in['data'])) uhp_error("no data argument");
            $scenario = $uhp::UHP_ON_NEW;
            if (isset($in['scenario']))  $scenario = (int) $in['scenario'];            
            $r['reply'] = $uhp->routingApply($in['data'], $scenario);            
            break;
        case 'uhpcommand':
            if (!isset($in['cmd'])) uhp_error("no cmd argument");
            $r['reply'] = @$uhp->UHPCommand($in['cmd'], $in['arg1'], $in['arg2'], $in['arg3']);
            break;
        case 'system_get':
            $r['reply'] = $uhp->getSystem();            
            break;
        case 'modulator_get':
            $r['reply'] = $uhp->getModulator();
            break;
        case 'demod1_get':
            $r['reply'] = $uhp->getDemodulator1();
            break;
        case 'demod2_get':
            $r['reply'] = $uhp->getDemodulator2();
            break;
        case 'log_get':
            $r['reply'] = $uhp->log();
            break;
        case 'rip_get':
            $r['reply'] = $uhp->ripGet();
            break;
        case 'rip_set':
            if (!isset($in['data'])) uhp_error("no data argument");
            $r['reply'] = $uhp->ripApply($in['data']);
            break;
        case 'dump':
            $r['reply'] = $uhp->dump();
            break;
        case 'load':
            if (!isset($in['data'])) uhp_error("no data argument");
            break;
        default:
            uhp_error("wrong opcode");
            break;
    }
} catch  (Exception $e) {
    uhp_error($e->getMessage());
}

$r['result'] = "ok";
echo json_encode($r);        



?>