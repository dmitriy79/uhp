<?php

const PROF_BASIC = 1 << 0; 
const PROF_RX    = 1 << 1; 
const PROF_TX    = 1 << 2;
const PROF_MOD   = 1 << 3;



const UHP_STATIONS_SETTABLESIZE = 1;
const UHP_STATIONS_SETON        = 2;
const UHP_STATIONS_OFF          = 3;
const UHP_STATIONS_SETSHAPER    = 4;
const UHP_STATIONS_SETREQPR     = 5;
const UHP_STATIONS_DELETE       = 6;


/*
    Функциональность
        - Список профилей
        - Просмотр/редактирование профиля
        - Смена активного профиля
        - Просмотр/редактирование site
        - Просмотр/редактироване/создание станции на хабе
        - Просмотр списка активных станций со статистикой
        - Просмотр/редактирование списка роутинга/ipадресов

    - Overview                  - FIX-ME
        - Site setup            - DONE
        - Profiles              - DONE
    -Advanced 
        - IP routing            - DONE.
        - IP protocols
            - SNMP
            - DHCP
            - ARP
            - NAT
            - RIP               - DONE
            - SNTP
            - RTP
            - TFTP
            - Multicast
            - Acceleration
            - Other Settings    - DONE
        - QoS 
            - Policies          - Partal DONE
            - Shapers
            - Real-time 
            - Service-mon
        -Network
            - Overview 
            - Stations          - DONE
            - MF-TDMA
            - ACM
            - STLC/NMS/Red
            - COTM/AMIP
            - Beam Sw 
        - System 
            - General
            - Interfaces
            - Ethernet
            - Demodulator
            - Modulator
            - Time-related
            - User access       - DONE
            - Flash/Boot
            - Save/load         - DONE
    - Maintenance
        - Support info
        - Log                   - DONE
        - Pointing
        - Spectrum analyzer
        - Network script        - DONE
        - Tuning
        - Traffic generator
        - Reboot                - DONE
    - Save config               - DONE



*/


require "simple_html_dom.php";

class UHPRULES
{

}

class UHP
{
    private $use_auth = false;
    private $login    = "admin";
    private $pwd      = "123";
    private $host     = "172.16.101.66";
    private $debug    = false;
    private $profile  = NULL;
    private $profile_default = "uhp1000_3_4_2_7";
    private $ips_ignore = array();

    const UHP_ON_DEFAULT = 1 << 0;
    const UHP_ON_APPLY   = 1 << 1 ;
    const UHP_ON_DELETE  = 1 << 2;
    const UHP_ON_NEW     = 1 << 3;
    const UHP_ON_SAVE    = 1 << 4;
    const UHP_ON_LOAD    = 1 << 5;

    const UHP_CHECKBOX   = 1;
    const UHP_INPUT      = 2;
    const UHP_SELECT     = 3;
    const UHP_RADIO      = 4;
    const UHP_INPUTFLOAT = 5;
    const UHP_CONST      = 6;
    const UHP_PROFILEID  = 30;


    const UHP_CMD_CFGSAVE       = 1; // arg: save bank no
    const UHP_CMD_CFGLOAD       = 2; // arg: bank or default
    const UHP_CMD_TFTPSAVE      = 3; // arg - filename
    const UHP_CMD_TFTPLOAD      = 4; // arg - filename
    const UHP_CMD_PWDSET        = 5; // arg1 =  user password arg2 - admin password
    const UHP_CMD_SETIPCREENING = 6; // arg1 = 0-AUTO, 1-ON, 2-OFF
    const UHP_CMD_NETWORKSCRIPT = 7; // arg1 -  type (0-local, 1 - unicast(SN), 2 - broadcast), arg2 - S/N,  arg3 - commands
    const UHP_CMD_REBOOT        = 8; 



    function UHP($host =  "192.168.222.222", $login = "", $pwd="")
    {
        $this->host =  $host;
        if (strlen($login) > 0 && strlen($pwd)>0)
        {
            $this->use_auth = true;
            $this->login = $login;
            $this->pwd   = $pwd;
        }
        $this->SetProfile($this->profile_default);
    }

    function page($url)
    {
        $final_url = "http://".$this->host."/".$url;
        $ctx = stream_context_create(array('http'=>
            array(
                'timeout' => 1,  //1200 Seconds is 20 Minutes
            )
        ));
        $res = file_get_contents($final_url);
        if (!$res)
        {
            $error = error_get_last();
            print_r($error);
            throw new Exception($error['message']);
            
        }
        $res = $this->fixtags($res);
        return $res;
    }

    function fixtags($html)
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        return $doc->saveHTML();
    }

    public function ignoreIP($ips)
    {
        $this->ips_ignore = $ips;
    }


    function uhpinfo()
    {
    }

    function debug($d)
    {
        $this->debug = $d;
    }

    function SetProfile($name)
    {
        require_once($name.".php");
        $this->profile = new $name();
    }


    function __call($name, $args)
    {
//        echo "call $name!!!!\n";
        return $this->profile->$name($args);
    
    }


    private function rulesWriteProcess($rules, $p, $on = self::UHP_ON_APPLY)
    {
//        print_r($p);
        foreach ($rules as $key => $prule) 
        {
            foreach($prule as $pkey => $rule)
            {
                if (isset($rule['on']) &&  !((int)$rule['on'] & (int)$on))
                {
//                    echo ">>>>>>>>>>>>>>>>>>>>> SKIP RULE\n";
//                    print_r($rule);
                    continue;
                }
                // Get correct assoc name from rule
                $eres = explode("->", $rule['local']);
                switch(count($eres))
                {
                    case 2:
                        $val = $p[$eres[0]][$eres[1]];
                        break; 
                    default:
                        $val = $p[$rule['local']];
                        break;
                }
                //echo "val = $val \n";
                switch($rule['tpe'])
                {
                    case self::UHP_INPUTFLOAT:
                        $whole = floor($val); 
                        $fraction = ($val - $whole) * 10;
                        $data[$rule['name']] = $whole;
                        $data[$rule['name2']] = $fraction;
                        break;
                    case self::UHP_CONST:
                        $data[$rule['name']] = $rule['value'];
                        break;
                    case self::UHP_CHECKBOX:
                        if ($val)  $data[$rule['name']] = $val;
                        break;        
                    default:
                        $data[$rule['name']] = $val;
                        break;
                }

            }
            $urlkey = $key;
            $urlkey[1] = strtoupper($urlkey[1]); // convert secont letter to uppercase

            if ($rules[$key]['options'])
            {
                if (isset($rules[$key]['options']['writeurl']))
                    $urlkey = $rules[$key]['options']['writeurl'];
            }
//            print_r($data);
            $url = $urlkey."?".http_build_query($data);
            if ($this->debug) echo "rulesWriteProcess:URL= $url \n";
            $this->page($url);    
            unset($data);
        }
    }

    private function rulesReadProcess($rules, $urladd="", $bh = NULL)
    {
        foreach ($rules as $key => $prule) 
        {
            if ($bh == NULL)
            {
                $bh  = new simple_html_dom();  
//                echo ">>>>> ".$key.$urladd."<<<<<\n";
                $page = $this->page($key.$urladd);
                if ($this->debug)
                    echo "rulesReadProcess:URL=".$key.$urladd."\n";
                $bh->load($page);
            }
//            echo ">>>>>>>>>> INCOMING:\n";
//            print_r($prule);
            foreach($prule as $pkey => $rule)
            {
//                echo "\n------------------------$pkey-------------------------------\n";
                if (!is_numeric($pkey) && $pkey == "options") continue;
                $gotcha = true;    
  //              echo "Process rule:\n";
//                print_r($rule);
                switch($rule['tpe'])
                {
                    case self::UHP_INPUT:
                        $val =  $bh->find("input[name=".$rule["name"]."]", 0)->value;
//                        echo "UHP_INPUT VAL = $val \n";
                        break;
                    case self::UHP_INPUTFLOAT:
                        $val =  (float) $bh->find("input[name=".$rule["name"]."]", 0)->value + ((float) $bh->find("input[name=".$rule["name2"]."]", 0)->value / 10) ;
                        break;
                    case self::UHP_CHECKBOX:
                        $val =  (string) $bh->find("input[name=".$rule["name"]."]", 0)->checked;
                        break;
                    case self::UHP_SELECT:
                        $select = $bh->find("select[name=".$rule["name"]."]", 0);
                        if ($select)
                        {
                            $val = $select->find("option[selected]", 0)->value;
                        } else
                            $gotcha = false;                                   
                        break;
                    case self::UHP_RADIO:
                        foreach($bh->find("input[name=".$rule["name"]."]") as $cb)
                            if ($cb->checked)
                                 $val = $cb->value;
                        break;
                    case self::UHP_PROFILEID:
                    case self::UHP_CONST:
                            $gotcha = false;
                        break;
                    default:
                        {
                            $gotcha = false;
                            echo "WARN! Unknown type!!!!!\n";
                            print_r($rule);
                            exit(-1);

                        }
                        break;
                }

                if ($gotcha)
                {
                    $eres = explode("->", $rule['local']);
                    switch(count($eres))
                    {
                        case 2:
                            $p[$eres[0]][$eres[1]] = $val;
                            break; 
                        default:
                            $p[$rule['local']] = $val;
                            break;
                    }
                }
                unset($val);

            }
            $bh = NULL;
        }
        return $p;

    }

    function siteLoad()
    {
        $rules = $this->siteRules();
        $site = $this->rulesReadProcess($rules);
        return $site;
    }

    function siteApply($site)
    {
        $rules = $this->siteRules();
        $this->rulesWriteProcess($rules, $site);
    }

    /* Load profile with id */
    function profileLoad($pid)
    {
        $p = array();        
        $rules = $this->profileRules();
        $p =  $this->rulesReadProcess($rules, "?da=".$pid);
        $p['id']  = $pid;
        return $p;
    }

    function profileApply($p)
    {
        $rules = $this->profileRules();
        $this->rulesWriteProcess($rules, $p);
    }

    /* Apply profiles loaded by profileList */
    function profilesApply($p)
    {
        foreach($p as $key => $record)
        {
            print_r($record);
//            echo "-------------\n";
            if (isset($record['profile']))
            {
                $this->profileApply($record['profile']);
            }
        }

    }

    function profileList($load_profiles =  false)
    {
        $page = $this->page("cc3");
        $profiles = array();
        $c = new simple_html_dom();        
        $c->load($page);
        if ($c)
        {            
            $fnd = $c->find('table', 0);
            if ($fnd)
            {
                foreach($fnd->find("tr") as $row)
                {
                    $num   = $row->find("td", 0);
                    if ($num)
                    {   
                        $pid = (int) $num->plaintext;
                        $profiles[$pid]['id']      = $pid;
                        $profiles[$pid]['mode']    = $row->find("td", 1)->plaintext;
                        $profiles[$pid]['valid']   = $row->find("td", 2)->plaintext == "+" ? true : false;
                        $profiles[$pid]['autorun'] =  $row->find("td", 3)->plaintext == "+" ? true : false;
                        $profiles[$pid]['title']   =  trim(str_replace("&nbsp;", "",  $row->find("td", 4)->plaintext));
                        $profiles[$pid]['runs']    =  (int) $row->find("td", 6)->plaintext;       
                        $profiles[$pid]['profile'] = $this->profileLoad($pid);
                    }
                }
            } 
        }
        return $profiles;
    }

    /* Run UHP profile by ID */
    function profileRun($profile_id)
    {
        $result = array();

        $profiles = $this->profilesGet();
        if (!isset($profiles[$profile_id]))
        {
            $result['result'] = "error";
            $result['error']  = "No such profile";
            return $result;
        }

        if (!$profiles[$profile_id]['valid'])
        {
            $result['result'] = "error";
            $result['error']  = "Profile not valid";
            return $result;
        }

        $page = $this->page("ck3?da=".(int) $profile_id);
        $profiles2 = $this->profilesGet();
        if ($profiles2[$profile_id]['runs'] > $profiles[$profile_id]['runs'] )
        {
            $result['result'] = "ok";
            $result['profile'] = $profiles[$profile_id];
        } else
        {
            $result['result'] = "error";
            $result['error'] = "Runs counter is same. Something wrong";
        }
        return $result;
    }

    /* 
        ------------- Station management ---------------- 
    */

    /* Load one stations by id */
    function stationGet($station_id)
    {
        $rules = $this->stationRules();
        $p =  $this->rulesReadProcess($rules, "?dq=1&db=".$station_id);
        $p['id'] = $station_id;
        return $p;
    } 

    /* change one station by id */
    function stationApply($station)
    {
        $rules = $this->stationRules();
        $this->rulesWriteProcess($rules, $station);
    }

    /* Get size of stations table */
    public function stationsTableSizeGet()
    {
        return $this->rulesReadProcess($this->stationTableRules());   
    }

    /* Set stations tables size */ 
    public function stationsTableSizeApply($table)
    {
        $this->rulesWriteProcess($this->stationTableRules(), $table);
    }



    /* Load all stations */
    function stationsGet()
    {
        $bh  = new simple_html_dom();  
        $stations = array();
        $page = $this->page("cc24");        
        $bh->load($page);
        $pages = 1;
  
        for($p=0;$p<$pages+1;$p++)
        {
            if ($p == 0)
                $url = "cc24";
            else
                $url = "cc24?dq=". (string)(($p-1) * 20)."&ta=Next20";
                        
//            echo "$p  $url \n";
            $page = $this->page($url);
            $bh->load($page);

            $ts = $bh->find("input[name=dk]", 0)->value;
            $pages = $ts / 20;
    
            $tb = $bh->find("table[width=400]", 0);    
            foreach($tb->find("tr") as $line)
            {   
                $key = $line->find("td", 0)->plaintext;
                if (!is_numeric($key)) continue;
                $on = $line->find("td", 1)->plaintext == "+" ? true : false;             
                $stations[$key]['id']         =  $key;
                $stations[$key]['on']         =  $line->find("td", 1)->plaintext == "+" ? true : false;      
                $stations[$key]['serial']     =  (int) $line->find("td", 2)->plaintext;
                $stations[$key]['redserial']  =  (int) $line->find("td", 3)->plaintext;
                $stations[$key]['shaper']     =  (int) $line->find("td", 4)->plaintext;
                $stations[$key]['reqprio']    =  (int) $line->find("td", 5)->plaintext;
                $stations[$key]['accel']      =  (int) $line->find("td", 5)->plaintext == "+" ? true : false;            
            }
        }
        //print_r($stations);
        return $stations;
    }

    public function stationsApply($stations)
    {
        foreach($stations as $key => $station)
        {
            $this->stationApply($station);
        }
    }

    function stationsOnline($format = 1)
    {
        $stations = array();
        $bh  = new simple_html_dom();  

        $process = true;
        $off = 1;
        while($process)
        {            
            $page = $this->page("ss24?db=0&da=".$off);        
            $bh->load($page, true, false); // do not strip \r\n
            $str = $bh->find("pre", 0)->plaintext;
            $lines = explode("\n", $str);
            $parsed = 0;
            foreach($lines  as $line)
            {
    //            echo $line."\n";
                $line = str_replace("|", "", $line); // remove '|'
                $line = preg_replace('/\s+/', ' ',$line); // remove spaces
                $p = explode(" ", $line);
                if (!is_numeric($p[0])) continue; 
                $i=1;
                $key = $p[0];
                $stations[$key]['rxbytes'] = (int) $p[$i++];
                $stations[$key]['crcerrs'] = (int) $p[$i++];
                $stations[$key]['req']     =       $p[$i++];
                $stations[$key]['all']     = (int) $p[$i++];
                $stations[$key]['hbrx']    =  $p[$i++];
                $stations[$key]['rf']      = (int) $p[$i++];
                if ($stations[$key]['hbrx'] != "-") 
                    $stations[$key]['hba']       = (int) $p[$i++];
                
                $stations[$key]['strx']    = $p[$i++];
                $stations[$key]['sttx']    = $p[$i++];
                $stations[$key]['sta']       = (int) $p[$i++];
                $stations[$key]['state']   =   $p[$i++];
                $stations[$key]['updown']      = (int) $p[$i++];
                $parsed++;
            }
            if ($parsed != 30)
                break;
            $off += 30;
        }
        return $stations;
    }


    /* 
        ----------------------- UHP IP addresses and routing ------------------------
     */


    /* get S-type routing (Static route) key - ct4 */
    public function routingSTypeGet($id)
    {
        $rules = $this->staticRouteRules();
        $p = $this->rulesReadProcess($rules, "?da=".$id);            
        $p['type'] = "Static route";
        return $p;
    }

    public function routingVTypeGet($id)
    {
        $rules = $this->svlanReceiveRules();
        $p =  $this->rulesReadProcess($rules, "?da=".$id);
        $p['type'] = "SVLAN receive";
        return $p;
    }

    /* get M-type routing  ___ VLAN bridge or TX map ___. KEY- cm4 */
    public function routingMTypeGet($id)
    {
        $bh  = new simple_html_dom();          
        $page = $this->page("cm4?da=".(int) $id); 
        $bh->load($page);
        $title = $bh->find("h3", 0)->plaintext;
        if ($title == "TX map")
            $rules = $this->txMapRules();           
        else
            $rules = $this->vlanBridgeRules(); // VLAN bridge

        $p =  $this->rulesReadProcess($rules, "?dq=1&db=".$id, $bh);   
        if ($title == "TX map")
            $p['type'] = "TX map";
        else
            $p['type'] = "VLAN bridge";

        return $p;
    }


    /* get A-type routing  IP address only. A little strange shit. */
    public function routingATypeGet($id)
    {
        $bh  = new simple_html_dom();          
        $page = $this->page("ci4?da=".(int) $id); 
        $bh->load($page);
        $rules = $this->ipaddrRules();
        $p =  $this->rulesReadProcess($rules, "".$id, $bh);
        $p['type']  = "IP Address";
        return $p;
    }


    public function routingApply($p, $scenario = self::UHP_ON_APPLY)
    {
        switch($p['type'])
        {
            case "IP Address":
                $rules = $this->ipaddrRules();   
                break;
            case "Static route":
                $rules = $this->staticRouteRules();
                break;
            case "TX map":
                $rules = $this->txMapRules();
                break;
            case "VLAN bridge":
                $rules = $this->vlanBridgeRules();
                break;
            case "SVLAN receive":
                $rules = $this->svlanReceiveRules();
                break;
            default:
                echo "!!!!!!!!!!!!UKNOWN TYPE ".$p['type']."\n";
                return;
                break;
        }

        if ($scenario == self::UHP_ON_NEW || $scenario == self::UHP_ON_DELETE)        
        {
            printf("\n>>>>>Use ID PRELOAD<<<<\n");
            $da = ($scenario == self::UHP_ON_NEW) ? 0 : $p['id'];
            $blank = $this->rulesReadProcess($rules, "?da=".$da);
            $p['rid'] = $blank['rid'];
  //          printf("\n>>>>>Use ID PRELOAD<<<<\nBLANK:\n");
//            print_r($blank);
            
        }
        $this->rulesWriteProcess($rules, $p, $scenario);
    }


      /*
        Routing types:  
        M - VLAN bridge   - cm4 - done 
        M - TX map        - cm4 - done
        A - IP address    - ci4 - done - set done
        S - Static Route  - ct4 - done - set done
        V - SVLAN receive - cx4 - done
    */
    public function  routingTableGet()
    {
        $bh  = new simple_html_dom();  
        $rt = array();

        $page = $this->page("cc4");  
        $bh->load($page);
        $t = $bh->find("table", 0);
        if ($t)
        {
            foreach($t->find("tr") as $tr)
            {
                $type = trim($tr->find("td", 0)->plaintext);                
                list($magic, $stuff) = explode("?", $tr->find("a", 0)->href);
                list($param, $key)   = explode("=", $stuff);

                if (strlen($type) == 0 || $type == "Next" || $key == "") continue;
                
                switch($magic)
                {
                    case "cm4": 
                        $rt[$key] = $this->routingMTypeGet($key);
                        break;
                    case "ci4":
                        $rt[$key] = $this->routingATypeGet($key);
                        break;
                    case "ct4":
                        $rt[$key] = $this->routingSTypeGet($key);
                        break;
                    case "cx4":
                        $rt[$key] = $this->routingVTypeGet($key);
                        break;
                }                    
            }
        }
     
        return $rt;
    }

    /* clean all routing records */
    public function routingTableClean($smart = true)
    {
        // ❤❤❤❤❤❤❤ Need reload routing table every time!!!!!! ❤❤❤❤❤❤❤
        do
        {
            $records = $this->routingTableGet();
            if (count($records) == 0) return;
    
            $gotcha = false;

            foreach($records as $key => $record)
            {
                if ($smart && $record['type'] == "IP Address" && ($record['ipaddr'] == $this->host || in_array($record['ipaddr'], $this->ips_ignore)))
                    continue;
                
                
                $this->routingApply($record, self::UHP_ON_DELETE);
                $gotcha = true;
                break;
            }
        } while($gotcha == true);
        
    }

    /* 
        apply routing table (routingTableGet)
        smart 
     */
    public function routingTableApply($rtable, $clean =  false, $smart = true) 
    {
        if ($clean) $this->routingTableClean($smart);
        foreach($rtable as $key => $route)
        {
            echo "Apply: ".$route['ipaddr']."\n";
            print_r($route);
            if ($smart && $route['type'] == "IP Address" && ($route['ipaddr'] == $this->host || in_array($route['ipaddr'], $this->ips_ignore)))
            {
                echo ">>>>>>IGNORE <<<<<<<<\n";
                continue;
            }
            
            $this->routingApply($route, $scenario = self::UHP_ON_NEW);
        }
    }


    function UHPCommand($cmd, $arg1 = "", $arg2 = "", $arg3 = "")
    {
        switch($cmd)
        {
            case self::UHP_CMD_CFGSAVE:
            case self::UHP_CMD_CFGLOAD:
                $rules = $self->flashLoadSaveRules();
                $p['cfgflashbank'] = 0;
                if ($arg1 != "") $p['cfgflashbank'] = $arg1;            
                $this->rulesWriteProcess($rules, $p,  $cmd == self::UHP_CMD_CFGLOAD ?  self::UHP_ON_LOAD : self::UHP_ON_SAVE);
                break;
            case self::UHP_CMD_TFTPSAVE:
            case self::UHP_CMD_TFTPLOAD:
                $rules = $self->tftpLoadSaveRules();
                $p['tftpfile'] = "uhp_config";
                if ($arg1 != "") $p['tftpfile'] = $arg1;            
                $this->rulesWriteProcess($rules, $p,  $cmd == self::UHP_CMD_TFTPLOAD ?  self::UHP_ON_LOAD : self::UHP_ON_SAVE);
                break;
            case self::UHP_CMD_PWDSET:
                $rules = $self->userAccessRules();
                $p['userpwd'] = $arg1;
                $p['adminpwd'] = $arg2;
                $this->rulesWriteProcess($rules, $p);
                break;
            case self::UHP_CMD_SETIPCREENING:
                $rules = $this->ipScreeningRules();
                if ($arg1 == "") return;$
                $p['ipscreening'] = (int) $arg1;
                $this->rulesWriteProcess($rules, $p);
                break;
            case self::UHP_CMD_NETWORKSCRIPT:
                if ($arg1 == "" || $arg2 == "" || $arg3 == "") return;
                $rules = $this->networkScriptRules();
                $p['type']  = $arg1;
                $p['sn']    = $arg2;
                $p['cmd']   = $arg3;
                $this->rulesWriteProcess($rules, $p);
                break;
            case self::UHP_CMD_REBOOT:
                $this->page("cw51?ta=Reboot");
                break;
            default:
                return;
                break;
        }

    }


    /*  ------------ RIP ---------------- */
    public function ripGet()
    {                
        return  $this->rulesReadProcess($this->ripRules());
    }

    public function ripApply($rip)
    {
        $this->rulesWriteProcess($this->ripRules(), $rip);
    }

    /* --------------------- Log ------------------ */
    public function log()
    {
        $log = array();
        $idx = 0;
        $bh  = new simple_html_dom(); 
        for($p = 0; $p < 17; $p++) 
        {
            $page = $this->page("ss52?db=". ($p * 30));
            $bh->load($page, true, false); // do not strip \r\n
            $chunk = $bh->find("pre", 0)->plaintext;
            foreach(explode("\n", $chunk) as $line)
            {
                list($ts, $s) = explode("             ", $line);
                $log[$idx]['ts'] = trim($ts);
                $log[$idx]['s'] = trim($s);
                if (strlen($log[$idx]['ts']) != 0) $idx++;
            }
        }
        unset($log[$idx]);
        return $log;
    }

    private function PageRegexp($rules)
    {        
        $bh  = new simple_html_dom(); 
        $bh->load($this->page($rules['url']), true, false); 
        $pre =  html_entity_decode($bh->find("pre", 0)->plaintext);    
//        echo $pre;
//        echo "Apply regexp:".$rules['regexp']."\n";
        preg_match_all($rules['regexp'], $pre, $matches, PREG_SET_ORDER, 0);
//        print_r($matches);
        if (!$matches) return;
        foreach($matches[0] as $key => $m)
        {
            $hk = $rules['keys'][$key-1];
            if ($hk)
                $ret[$hk] = trim($m);
        }
        return $ret;

    }

    public function getSystem()
    {
        $rules =  $this->systemPageRules();
        $s = $this->PageRegexp($rules);
        $s['options'] = explode(" ", $s['options']);
//        print_r($s);
        return $s;
    }


    public function getNet()
    {
        
    }

    public function getModulator()
    {
//        echo "getModulator!\n";
        $rules =  $this->modulatorPageRules();
//        print_r($rules);
        $s = $this->PageRegexp($rules);
//        print_r($s);

    }

    public function getDemodulator1()
    {        
        $rules =  $this->demodulator1PageRules();
        print_r($rules);
        $s = $this->PageRegexp($rules);
        print_r($s);
    }

    public function overview() // NEEED FIXES!!!!!!!!!!!!!
    {
        $page = $this->page("ss40");
        $profiles = array();
        $c = new simple_html_dom();        
        $c->load($page);
        $r = array();
        if ($c)
        {       
            // Base top Refresh	SN: 20030347	SW: UHP-240 Software	Ver: 3.4.3 (27.06.2018)     
            $hdr = $c->find('table', 0);
            if ($hdr)
            {
                $r['sn'] = trim($hdr->find("td", 1)->find("b", 0)->plaintext);
                $r['sw'] = trim($hdr->find("td", 2)->find("b", 0)->plaintext);
                $r['ver'] = trim($hdr->find("td", 3)->find("b", 0)->plaintext);
            }

            $state = $c->find('table', 1);        
            if ($state)
            {
                $r['cpuload'] = trim($state->find("td", 1)->find("b", 0)->plaintext);
                $r['temp'] = trim($state->find("td", 2)->find("b", 0)->plaintext);
                $r['profile'] = trim($state->find("td", 3)->find("b", 0)->plaintext);
            }

            // ----------------------- interfaces table
            $ifaces = $c->find('table', 2);
            if ($ifaces)
            {
                $eth =  $ifaces->find("tr", 2);
                if ($eth)
                {
                    $r['eth']['state']   = $eth->find("td", 1)->plaintext; 
                    $r['eth']['info']    = $eth->find("td", 2)->find("b", 0)->plaintext;
                    $r['eth']['txrate']  = $eth->find("td", 3)->plaintext;
                    $r['eth']['rxrate']  = $eth->find("td", 4)->plaintext; 
                    $r['eth']['rxerrs']  = $eth->find("td", 5)->plaintext;
                    preg_match_all('/(\d+).*/m', $r['eth']['rxerrs'], $matches, PREG_SET_ORDER, 0); // <<< FOKING HACK cause bad-html
                    $r['eth']['rxerrs']  = $matches[0][1];

                }

                $dm1 = $ifaces->find("tr", 3);
                if ($dm1)
                {
                    $r['demod1']['state']   = $dm1->find("td", 1)->plaintext; //->find("a", 0)->plaintext;
                    $r['demod1']['info']    = $dm1->find("td", 2)->find("b", 0)->plaintext;
                    $r['demod1']['txrate']  = $dm1->find("td", 3)->plaintext;
                    $r['demod1']['rxrate']  = $dm1->find("td", 4)->plaintext;
                    $r['demod1']['rxerrs']  = $dm1->find("td", 5)->plaintext;  
                    preg_match_all('/(\d+).*/m', $r['demod1']['rxerrs'], $matches, PREG_SET_ORDER, 0);
                    $r['demod1']['rxerrs']  = $matches[0][1];
                    unset($matches);
                }

                $dm2 = $ifaces->find("tr", 4);
                if ($dm2)
                {
                    $r['demod2']['state']   = $dm2->find("td", 1)->plaintext; //->find("a", 0)->plaintext;
                    $r['demod2']['info']    = $dm2->find("td", 2)->find("b", 0)->plaintext;
                    $r['demod2']['txrate']  = $dm2->find("td", 3)->plaintext;
                    $r['demod2']['rxrate']  = $dm2->find("td", 4)->plaintext;
                    $r['demod2']['rxerrs']  = $dm2->find("td", 5)->plaintext;  
                    preg_match_all('/(\d+).*/m', $r['demod2']['rxerrs'], $matches, PREG_SET_ORDER, 0);
                    $r['demod2']['rxerrs']  = $matches[0][1];
                }

                $mod = $ifaces->find("tr", 5);
                if ($mod)
                {
                    $r['mod']['state']   = $mod->find("td", 1)->plaintext; //->find("a", 0)->plaintext;
                    $r['mod']['info']    = $mod->find("td", 2)->find("b", 0)->plaintext;
                    $r['mod']['txrate']  = $mod->find("td", 3)->plaintext;
                    $r['mod']['rxrate']  = $mod->find("td", 4)->plaintext;
                    $r['mod']['rxerrs']  = $mod->find("td", 5)->plaintext;  
                    preg_match_all('/(\d+).*/m', $r['demod2']['rxerrs'], $matches, PREG_SET_ORDER, 0);
                    $r['mod']['rxerrs']  = $matches[0][1];
                }

                $net = $ifaces->find("tr", 6);
                if ($net)
                {
                    $r['net']['state']   = $net->find("td", 1)->plaintext; //->find("a", 0)->plaintext;
                    $r['net']['info']    = $net->find("td", 2)->find("b", 0)->plaintext;
                    $r['net']['txrate']  = $net->find("td", 3)->plaintext;
                    $r['net']['rxrate']  = $net->find("td", 4)->plaintext;
                    $r['net']['rxerrs']  = $net->find("td", 5)->plaintext;  
                    preg_match_all('/(\d+).*/m', $r['net']['rxerrs'], $matches, PREG_SET_ORDER, 0);
                    $r['net']['rxerrs']  = $matches[0][1];
                }


                
            }

            // Stations	Bandwidth	TDMA table
            $sbt = $c->find('table', 3);
            if ($sbt)
            {
                $l1 =  $sbt->find("tr", 1);
                if ($l1)
                {
                    $r['tdma']['st_enabled'] = $l1->find("td", 1)->plaintext;
                    $r['tdma']['total_req']  = $l1->find("td", 3)->plaintext;
                    $r['tdma']['br_rf_lvl']  = $l1->find("td", 5)->plaintext;
                    preg_match_all('/(.*)dBm*.+/m', $r['tdma']['br_rf_lvl'], $matches, PREG_SET_ORDER, 0);
                    $r['tdma']['br_rf_lvl']  = $matches[0][1];                            
                }
                $l2 =  $sbt->find("tr", 2);
                if ($l2)
                {
                    $r['tdma']['st_online']  = $l2->find("td", 1)->plaintext;
                    $r['tdma']['rt_req']     = $l2->find("td", 3)->plaintext;
                    $r['tdma']['fp_lost']    = $l2->find("td", 5)->plaintext;
                    preg_match_all('/(\d+).*/m', $r['tdma']['fp_lost'], $matches, PREG_SET_ORDER, 0);
                    $r['tdma']['fp_lost']  = $matches[0][1];
                }

                $l3 =  $sbt->find("tr", 3);
                if ($l3)
                {
                    $r['tdma']['st_active']  = $l3->find("td", 1)->plaintext;
                    $r['tdma']['cir_req']    = $l3->find("td", 3)->plaintext;
                    $r['tdma']['tts_tdt']    = $l3->find("td", 5)->plaintext;
                    preg_match_all('/(.*)us/m', $r['tdma']['tts_tdt'], $matches, PREG_SET_ORDER, 0);
                    $r['tdma']['tts_tdt']  = $matches[0][1];                            
                }

                $l4 =  $sbt->find("tr", 4);
                if ($l4)
                {
                    list($r['tdma']['hubcn_low'], $r['tdma']['hubcn_high'])   = explode("/", $l4->find("td", 1)->plaintext);
                    $r['tdma']['req_slots']   = $l4->find("td", 3)->plaintext;
                    $r['tdma']['tts_errs']    = $l4->find("td", 5)->plaintext;
                    preg_match_all('/(\d+).*/m', $r['tdma']['tts_errs'], $matches, PREG_SET_ORDER, 0);
                    $r['tdma']['tts_errs']  = $matches[0][1];                            
                }

                // Stations	Bandwidth	TDMA
                $l5 =  $sbt->find("tr", 5);
                if ($l5)
                {
                    list($r['tdma']['rembcn_low'], $r['tdma']['remcn_high'])   = explode("/", $l5->find("td", 1)->plaintext);
                    $r['tdma']['load']   = $l5->find("td", 3)->plaintext;
                    $r['tdma']['act_channels']    = $l5->find("td", 5)->plaintext;
                    preg_match_all('/(\d+).*/m', $r['tdma']['act_channels'], $matches, PREG_SET_ORDER, 0);
                    $r['tdma']['act_channels']  = $matches[0][1];                            
                }
            }
                        
            
            // TDM Channel	ACM MODCOD	C/N limit	Stations	Frames TX	Bytes TX
            $acm = $c->find('table', 4);
            if ($acm)
            {
                $rows =  $acm->find("tr");
                foreach($rows as $row)
                {
                    $id = @$row->find("td", 0)->plaintext;
                    if (!is_numeric($id)) continue;
                    $r['tdma_acm'][$id]['modcod'] = $row->find("td", 1)->plaintext;
                    $r['tdma_acm'][$id]['cnlimit'] = $row->find("td", 2)->plaintext;
                    $r['tdma_acm'][$id]['stations'] = $row->find("td", 3)->plaintext;
                    $r['tdma_acm'][$id]['frames_tx'] = $row->find("td", 4)->plaintext;
                    $r['tdma_acm'][$id]['bytes_tx'] = $row->find("td", 5)->plaintext;
                }
            }

            $tdmacm = $c->find('table', 5);
            if ($tdmacm)
            {   
                $rows =  $tdmacm->find("tr");
                foreach($rows as $row)
                {
                    @$id = $row->find("td", 0)->plaintext;
                    if (!is_numeric($id)) continue;
                    $r['tdm_acm'][$id]['modcod'] = $row->find("td", 1)->plaintext;
                    $r['tdm_acm'][$id]['cnlimit'] = $row->find("td", 2)->plaintext;
                    $r['tdm_acm'][$id]['stations'] = $row->find("td", 3)->plaintext;
                    $r['tdm_acm'][$id]['frames_rx'] = $row->find("td", 4)->plaintext;
                    $r['tdm_acm'][$id]['bytes_rx'] = $row->find("td", 5)->plaintext;
                    $r['tdm_acm'][$id]['crcerrs'] = $row->find("td", 6)->plaintext;
                }
            }



//            echo $ifaces->outertext; 
        }
        return $r;
    }      


    public function dump()
    {
        $dump = array();
        $dump['profiles']             = $this->profileList(true);
        $dump['stations_table']       = $this->stationsTableSizeGet();
        $dump['stations']             = $this->stationsGet();        
        $dump['routing']              = $this->routingTableGet();
        $dump['protocols']['snmp']    = NULL;
        $dump['protocols']['dhcp']    = NULL;
        $dump['protocols']['arp']     = NULL;
        $dump['protocols']['nat']     = NULL;    
        $dump['protocols']['rip']     = $this->ripGet();
        $dump['protocols']['sntp']    = NULL;
        $dump['protocols']['rtp']     = NULL;
        $dump['protocols']['tftp']    = NULL;
        $dump['protocols']['mcast']   = NULL;
        $dump['protocols']['accel']   = NULL;
        return $dump;    
    }



    public function load($d)
    {
        if (isset($d['profiles']))       $this->profilesApply($d['profiles']);        
        if (isset($d['stations_table'])) $this->stationsTableSizeApply($d['stations_table']);
        if (isset($d['stations']))       $this->stationsApply($d['stations']);
        if (isset($d['routing']))
        {
            $this->routingTableClean();
        }

        

    }



}

?>
