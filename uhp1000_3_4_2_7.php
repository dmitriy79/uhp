<?php

class uhp1000_3_4_2_7 extends UHPRULES
{

    private $modcods  = array(
        2  => "QPSK-1/3",
        3  => "QPSK-2/5",
        4  => "QPSK-1/2",
        5  => "QPSK-3/5",
        6  => "QPSK-2/3",
        7  => "QPSK-3/4",
        8  => "QPSK-4/5",
        9  => "QPSK-5/6",
        10 => "QPSK-8/9",
        12 => "8PSK-3/5",
        13 => "8PSK-2/3",
        14 => "8PSK-3/4",
        15 => "8PSK-5/6",
        16 => "8PSK-8/9",
        18 => "16APSK-2/3",
        19 => "16APSK-3/4",
        20 => "16APSK-4/5",
        21 => "16APSK-5/6",
        22 => "16APSK-8/9",
    );

    private $damafec = array(
        0 => "QPSK-2/3",
        1 => "QPSK-5/6",
        2 => "8PSK-2/3",
        3 => "8PSK-5/6",
        4 => "BPSK-2/3",
        5 => "BPSK-5/6",
    );

    private $mods_uhp1000  = array(
        0  => "none",
        1  => "SCPC modem",
        2  => "Star station",
        3  => "Mesh station",
        4  => "Hubless station",
        5  => "DAMA station",
        6  => "CrossPol test",
        9  => "Star hub", 
        10 => "MF hub",
        11 => "Outroute",
        12 => "Inroute",
        13 => "MF inroute",
        14 => "Hubless master",
        15 => "DAMA hub",
        16 => "DAMA inroute",
        17 => "Channel simulator",
    );

    private $mods_uhp200  = array(
        0  => "none",
        1  => "SCPC modem",
        2  => "Star station",
        3  => "Mesh station",
        4  => "Hubless station",
        5  => "DAMA station",
        6  => "CrossPol test",
        9  => "Star hub", 
        10 => "MF hub",
        11 => "Outroute",
        12 => "Inroute",
        13 => "MF inroute",
        14 => "Hubless master",
        15 => "DAMA hub",
        16 => "DAMA inroute",
        17 => "Channel simulator",
        18 => "Spectrum Analyzer",
        19 => "Commissioning unit",
    );


    private $tdmarf_fec = array(
        0  => "BPSK-1/2",
        1  => "BPSK-2/3",
        2  => "BPSK-3/4",
        3  => "BPSK-5/6",
        4  => "QPSK-1/2",
        5  => "QPSK-2/3",
        6  => "QPSK-3/4",
        7  => "QPSK-5/6",
        8  => "8PSK-1/2",
        9  => "8PSK-2/3",
        10 => "8PSK-3/4",
        11 => "8PSK-5/6",
    );

    function ipaddrRules()
    {
        $r['ci4'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_INPUT);
        $r['ci4'][] = array('local' => "vlan",                'name' => 'dd', 'tpe' => UHP::UHP_INPUT);
        $r['ci4'][] = array('local' => "ipaddr",              'name' => 'ib', 'tpe' => UHP::UHP_INPUT);
        $r['ci4'][] = array('local' => "mask",                'name' => 'mc', 'tpe' => UHP::UHP_INPUT);
        $r['ci4'][] = array('local' => "localaccess",         'name' => 'df', 'tpe' => UHP::UHP_CHECKBOX);
        $r['ci4'][] = array('local' => "title",               'name' => 'tc', 'tpe' => UHP::UHP_INPUT);
        $r['ci4'][] = array('local' => "rid",                 'name' => 'du', 'tpe' => UHP::UHP_INPUT); 
        $r['ci4'][] = array("value" => "Apply",               'name' => 'ta', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_APPLY  | UHP::UHP_ON_NEW);
        $r['ci4'][] = array("value" => "Delete",              'name' => 'ta', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_DELETE);
        $r['ci4']['options'] = array('writeurl' => 'ci4');
        return $r;
    }

    function staticRouteRules()
    {
        $r['ct4'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_INPUT);
        $r['ct4'][] = array('local' => "vlan",                'name' => 'dd', 'tpe' => UHP::UHP_INPUT);
        $r['ct4'][] = array('local' => "ipaddr",              'name' => 'ib', 'tpe' => UHP::UHP_INPUT);
        $r['ct4'][] = array('local' => "mask",                'name' => 'mc', 'tpe' => UHP::UHP_INPUT);
        $r['ct4'][] = array('local' => "gwip",                'name' => 'if', 'tpe' => UHP::UHP_INPUT);
        $r['ct4'][] = array('local' => "title",               'name' => 'tc', 'tpe' => UHP::UHP_INPUT);
        $r['ct4'][] = array('local' => "rid",                 'name' => 'du', 'tpe' => UHP::UHP_INPUT); 
        $r['ct4'][] = array("value" => "Apply",               'name' => 'ta', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_APPLY  | UHP::UHP_ON_NEW);
        $r['ct4'][] = array("value" => "Delete",              'name' => 'ta', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_DELETE);
        $r['ct4']['options'] = array('writeurl' => 'ct4');
        return $r;
    }
    
    function txMapRules()
    {
        $r['cm4'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_INPUT);
        $r['cm4'][] = array('local' => "vlan",                'name' => 'dd', 'tpe' => UHP::UHP_INPUT);
        $r['cm4'][] = array('local' => "ipaddr",              'name' => 'ib', 'tpe' => UHP::UHP_INPUT);
        $r['cm4'][] = array('local' => "mask",                'name' => 'mc', 'tpe' => UHP::UHP_INPUT);
        $r['cm4'][] = array('local' => "svlan",               'name' => 'de', 'tpe' => UHP::UHP_INPUT);
        $r['cm4'][] = array('local' => "station",             'name' => 'df', 'tpe' => UHP::UHP_INPUT);
        $r['cm4'][] = array('local' => "prio",                'name' => 'dg', 'tpe' => UHP::UHP_SELECT);
        $r['cm4'][] = array('local' => "policy",              'name' => 'dh', 'tpe' => UHP::UHP_SELECT);
        $r['cm4'][] = array('local' => "shaper",              'name' => 'di', 'tpe' => UHP::UHP_SELECT);
        $r['cm4'][] = array('local' => "title",               'name' => 'tc', 'tpe' => UHP::UHP_INPUT);
        $r['cm4'][] = array('local' => "rid",                 'name' => 'du', 'tpe' => UHP::UHP_INPUT); 
        $r['cm4'][] = array("value" => "Apply",               'name' => 'ta', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_APPLY  | UHP::UHP_ON_NEW);
        $r['cm4'][] = array("value" => "Delete",              'name' => 'ta', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_DELETE);  
        $r['cm4']['options'] = array('writeurl' => 'cm4');
        return $r;   
    }

    function vlanBridgeRules()
    {
        $r['cb4'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_INPUT);
        $r['cb4'][] = array('local' => "vlan",                'name' => 'dd', 'tpe' => UHP::UHP_INPUT);
        $r['cb4'][] = array('local' => "svlan",               'name' => 'de', 'tpe' => UHP::UHP_INPUT);
        $r['cb4'][] = array('local' => "station",             'name' => 'df', 'tpe' => UHP::UHP_INPUT);
        $r['cb4'][] = array('local' => "prio",                'name' => 'dg', 'tpe' => UHP::UHP_SELECT);
        $r['cb4'][] = array('local' => "policy",              'name' => 'dh', 'tpe' => UHP::UHP_SELECT);
        $r['cb4'][] = array('local' => "shaper",              'name' => 'di', 'tpe' => UHP::UHP_SELECT);
        $r['cb4'][] = array('local' => "title",               'name' => 'tc', 'tpe' => UHP::UHP_INPUT);
        $r['cb4'][] = array('local' => "rid",                 'name' => 'du', 'tpe' => UHP::UHP_INPUT); 
        $r['cb4'][] = array("value" => "Apply",               'name' => 'ta', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_APPLY  | UHP::UHP_ON_NEW);
        $r['cb4'][] = array("value" => "Delete",              'name' => 'ta', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_DELETE);        
        $r['cb4']['options'] = array('writeurl' => 'cb4');
        return $r;
    }

    function  svlanReceiveRules()
    {
        $r['cx4'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_INPUT);
        $r['cx4'][] = array('local' => "vlan",                'name' => 'dd', 'tpe' => UHP::UHP_INPUT);
        $r['cx4'][] = array('local' => "svlan",               'name' => 'db', 'tpe' => UHP::UHP_INPUT);
        $r['cx4'][] = array('local' => "title",               'name' => 'tc', 'tpe' => UHP::UHP_INPUT);
        $r['cx4'][] = array('local' => "rid",                 'name' => 'du', 'tpe' => UHP::UHP_INPUT); 
        $r['cx4'][] = array("value" => "Apply",               'name' => 'ta', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_APPLY  | UHP::UHP_ON_NEW);
        $r['cx4'][] = array("value" => "Delete",              'name' => 'ta', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_DELETE);
        $r['cx4']['options'] = array('writeurl' => 'cx4');
        return $r;
    }


    function stationRules()
    {
        $r['cc24'][] = array('local' => "id",                 'name' => 'db', 'tpe' => UHP::UHP_PROFILEID);
        $r['cc24'][] = array('local' => "on",                 'name' => 'dc', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cc24'][] = array('local' => "serial",             'name' => 'dn', 'tpe' => UHP::UHP_INPUT);
        $r['cc24'][] = array('local' => "redserial",          'name' => 'dm', 'tpe' => UHP::UHP_INPUT);
        $r['cc24'][] = array('local' => "shaper",             'name' => 'de', 'tpe' => UHP::UHP_SELECT);
        $r['cc24'][] = array('local' => "reqprio",            'name' => 'df', 'tpe' => UHP::UHP_SELECT);
        $r['cc24'][] = array('local' => "accel",              'name' => 'dd', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cc24'][] = array('local' => "hidden1",            'name' => 'dl', 'tpe' => UHP::UHP_INPUT);
        $r['cc24'][] = array('local' => "hidden2",            'name' => 'do', 'tpe' => UHP::UHP_INPUT);
        $r['cc24'][] = array('local' => "hidden3",            'name' => 'ds', 'tpe' => UHP::UHP_INPUT);
        $r['cc24'][] = array('local' => "hidden4",            'name' => 'dt', 'tpe' => UHP::UHP_INPUT);

        $r['cc24'][] = array("value" => "1",                  'name' => 'dq', 'tpe' => UHP::UHP_CONST); 
        $r['cc24']['options'] = array('writeurl' => 'cw24');
        return $r;
    }

    function stationTableRules()
    {
        $r['cc24'][] = array('local' => "tablesize",          'name' => 'dk', 'tpe' => UHP::UHP_INPUT);
        $r['cc24'][] = array('local' => "hidden1",            'name' => 'du', 'tpe' => UHP::UHP_INPUT);
        $r['cc24'][] = array("value" => "Change",             'name' => 'ta', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_APPLY); 
        $r['cc24']['options'] = array('writeurl' => 'cx24');
        return $r;
    }

    function siteRules()
    {
        $r['cc2'][] = array('local' => "name",                'name' => 'td', 'tpe' => UHP::UHP_INPUT);
        $r['cc2'][] = array('local' => "location->latdeg",    'name' => 'du', 'tpe' => UHP::UHP_INPUT);
        $r['cc2'][] = array('local' => "location->latmin",    'name' => 'dv', 'tpe' => UHP::UHP_INPUT);
        $r['cc2'][] = array('local' => "location->latns",     'name' => 'dw', 'tpe' => UHP::UHP_SELECT);

        $r['cc2'][] = array('local' => "location->londeg",    'name' => 'dx', 'tpe' => UHP::UHP_INPUT);
        $r['cc2'][] = array('local' => "location->lonmin",    'name' => 'dy', 'tpe' => UHP::UHP_INPUT);
        $r['cc2'][] = array('local' => "location->latew",     'name' => 'dz', 'tpe' => UHP::UHP_SELECT);

        $r['cc2'][] = array('local' => "rf->rxlo",            'name' => 'df', 'tpe' => UHP::UHP_INPUT);
        $r['cc2'][] = array('local' => "rf->rxpower",         'name' => 'dn', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cc2'][] = array('local' => "rf->rx10mhz",         'name' => 'do', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cc2'][] = array('local' => "rf->rxspinv",         'name' => 'dp', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cc2'][] = array('local' => "rf->rxscpcadj",       'name' => 'dr', 'tpe' => UHP::UHP_INPUT);
        $r['cc2'][] = array('local' => "rf->rxtdmaadj",       'name' => 'dq', 'tpe' => UHP::UHP_INPUT); 

        $r['cc2'][] = array('local' => "rf->txlo",            'name' => 'dd', 'tpe' => UHP::UHP_INPUT);
        $r['cc2'][] = array('local' => "rf->txpower",         'name' => 'dg', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cc2'][] = array('local' => "rf->tx10mhz",         'name' => 'dh', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cc2'][] = array('local' => "rf->rxspinv",         'name' => 'di', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cc2'][] = array('local' => "rf->rxtdmaadj",       'name' => 'dq', 'tpe' => UHP::UHP_INPUT);

        $r['cc2'][] = array('local' => "carrier->scpcmode",   'name' => 'dt', 'tpe' => UHP::UHP_INPUT);
        $r['cc2'][] = array('local' => "carrier->tdmamode",   'name' => 'ds', 'tpe' => UHP::UHP_SELECT);

        $r['cc2'][] = array('local' => "ident->netid",        'name' => 'dc', 'tpe' => UHP::UHP_INPUT);
        $r['cc2'][] = array('local' => "ident->rfid",         'name' => 'db', 'tpe' => UHP::UHP_INPUT);

        $r['cc2'][] = array('local' => "globaltxlevel",       'name' => 'dl', 'tpe' => UHP::UHP_INPUT);
        
        $r['cc2'][] = array("value" => "Apply",               'name' => 'tf', 'tpe' => UHP::UHP_CONST);

        $r['cc2']['options'] = array('writeurl' => 'cw2');
        
        return $r;
    }

    function profileRules()
    {
        // basic
        $r['cb3'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_PROFILEID);
        $r['cb3'][] = array('local' => "valid",               'name' => 'db', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cb3'][] = array('local' => "autorun",             'name' => 'dc', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cb3'][] = array('local' => "mode",                'name' => 'dd', 'tpe' => UHP::UHP_SELECT, 'list' => $this->mods_uhp200);
        $r['cb3'][] = array('local' => "timeout",             'name' => 'de', 'tpe' => UHP::UHP_INPUT);
        $r['cb3'][] = array('local' => "title",               'name' => 'ta', 'tpe' => UHP::UHP_INPUT);

        // TDM/SCPC RX
        $r['cr3'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_PROFILEID);

        // UHP 200
        $r['cr3'][] = array('local' => "rx->inputselect",     'name' => 'dj', 'tpe' => UHP::UHP_SELECT, 'list' => array(0 => "RX-1", 1 => "RX-2"));
        // No on UHP 200 !!!!
        $r['cr3'][] = array('local' => "rx->enable",          'name' => 'df', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cr3'][] = array("local" => "rx->freq",            'name' => 'db', 'tpe' => UHP::UHP_INPUT);        
        $r['cr3'][] = array("local" => "rx->rate",            'name' => 'dc', 'tpe' => UHP::UHP_INPUT);        
        $r['cr3'][] = array("local" => "rx->lnbpwr",          'name' => 'dl', 'tpe' => UHP::UHP_RADIO, 'list' => array(1 => "13V", 0 => "18V"));   

        // TDM/SCPC TX
        $r['ct3'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_PROFILEID);
        $r['ct3'][] = array("local" => "tx->freq",            'name' => 'db', 'tpe' => UHP::UHP_INPUT);  
        $r['ct3'][] = array("local" => "tx->rate",            'name' => 'dc', 'tpe' => UHP::UHP_INPUT);  
        $r['ct3'][] = array("local" => "tx->modcod",          'name' => 'de', 'tpe' => UHP::UHP_SELECT);  
        $r['ct3'][] = array("local" => "tx->pilots",          'name' => 'dg', 'tpe' => UHP::UHP_CHECKBOX);

        // Modulator
        $r['cm3'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_PROFILEID);
        $r['cm3'][] = array("local" => "mod->txonoff",        'name' => 'dd', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cm3'][] = array("local" => "mod->txlevel",        'name' => 'db', 'name2' => 'dc', 'tpe' => UHP::UHP_INPUTFLOAT);

        // TLC
        $r['cl3'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_PROFILEID);
        $r['cl3'][] = array("local" => "tlc->enable",         'name' => 'dh', 'tpe' => UHP::UHP_CHECKBOX);  
        $r['cl3'][] = array("local" => "tlc->maxtxlv",        'name' => 'di', 'tpe' => UHP::UHP_INPUT);  
        $r['cl3'][] = array("local" => "tlc->netown16",       'name' => 'db', 'tpe' => UHP::UHP_INPUT);  
        $r['cl3'][] = array("local" => "tlc->avgmin",         'name' => 'dc', 'tpe' => UHP::UHP_INPUT);  
        $r['cl3'][] = array("local" => "tlc->desiredcnhub",   'name' => 'dd', 'name2' => 'de', 'tpe' => UHP::UHP_INPUTFLOAT);  
        $r['cl3'][] = array("local" => "tlc->desiredcnsta",   'name' => 'df', 'name2' => 'dg', 'tpe' => UHP::UHP_INPUTFLOAT);  

        // ACM
        $r['ca3'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_PROFILEID);
        $r['ca3'][] = array("local" => "acm->enable",         'name' => 'db', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['ca3'][] = array("local" => "acm->maxmodcod",      'name' => 'dg', 'tpe' => UHP::UHP_SELECT); 
        $r['ca3'][] = array("local" => "acm->cnthres",        'name' => 'dj', 'name2' => 'dk', 'tpe' => UHP::UHP_INPUTFLOAT);

        // Timing 
        $r['ci3'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_PROFILEID);
        $r['ci3'][] = array("local" => "timing->mode",        'name' => 'de', 'tpe' => UHP::UHP_SELECT); 
        $r['ci3'][] = array("local" => "timing->value",       'name' => 'df', 'tpe' => UHP::UHP_INPUT); 
        $r['ci3'][] = array("local" => "timing->deg",         'name' => 'db', 'tpe' => UHP::UHP_INPUT); 
        $r['ci3'][] = array("local" => "timing->min",         'name' => 'dc', 'tpe' => UHP::UHP_INPUT); 
        $r['ci3'][] = array("local" => "timing->ew",          'name' => 'dd', 'tpe' => UHP::UHP_SELECT); 

        // TDMA RF  (Hubless station)
        $r['cd3'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_PROFILEID);
        // UHP 200
        $r['cd3'][] = array("local" => "tdmarf->inputselect", 'name' => 'd3', 'tpe' => UHP::UHP_SELECT, 'list' => array(0 => "RX-1", 1 => "RX-2"));

        $r['cd3'][] = array("local" => "tdmarf->rate",        'name' => 'db', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->acm",         'name' => 'db', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->fec",         'name' => 'dc', 'tpe' => UHP::UHP_SELECT, 'list' => $this->tdmarf_fec); 
        $r['cd3'][] = array("local" => "tdmarf->cnthresh",    'name' => 'd4', "name2" => "d5", 'tpe' => UHP::UHP_INPUTFLOAT); 

        //UHP 200
        $r['cd3'][] = array("local" => "tdmarf->rolloff5",    'name' => 'd6', 'tpe' => UHP::UHP_CHECKBOX); 

        $r['cd3'][] = array("local" => "tdmarf->rxfreq1",     'name' => 'dv', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq1",     'name' => 'dL', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon2",     'name' => 'dg', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq2",     'name' => 'dw', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq2",     'name' => 'dM', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon3",     'name' => 'dh', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq3",     'name' => 'dx', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq3",     'name' => 'dN', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon4",     'name' => 'di', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq4",     'name' => 'dy', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq4",     'name' => 'dO', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon5",     'name' => 'dj', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq5",     'name' => 'dz', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq5",     'name' => 'dP', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon6",     'name' => 'dk', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq6",     'name' => 'dA', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq6",     'name' => 'dQ', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon7",     'name' => 'dl', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq7",     'name' => 'dB', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq7",     'name' => 'dR', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon8",     'name' => 'dm', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq8",     'name' => 'dC', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq8",     'name' => 'dS', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon9",     'name' => 'dn', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq9",     'name' => 'dD', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq9",     'name' => 'dT', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon10",    'name' => 'do', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq10",    'name' => 'dE', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq10",    'name' => 'dU', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon11",    'name' => 'dp', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq11",    'name' => 'dF', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq11",    'name' => 'dV', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon12",    'name' => 'dq', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq12",    'name' => 'dG', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq12",    'name' => 'dW', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon13",    'name' => 'dr', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq13",    'name' => 'dH', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq13",    'name' => 'dX', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon14",    'name' => 'ds', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq14",    'name' => 'dI', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq14",    'name' => 'dY', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon15",    'name' => 'dt', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq15",    'name' => 'dJ', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq15",    'name' => 'd0', 'tpe' => UHP::UHP_INPUT); 

        $r['cd3'][] = array("local" => "tdmarf->rxtxon16",    'name' => 'du', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cd3'][] = array("local" => "tdmarf->rxfreq16",    'name' => 'dK', 'tpe' => UHP::UHP_INPUT); 
        $r['cd3'][] = array("local" => "tdmarf->txfreq16",    'name' => 'd1', 'tpe' => UHP::UHP_INPUT); 

        // TDMA protocol 
        $r['cp3'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_PROFILEID);
        $r['cp3'][] = array("local" => "tdmaprot->inrouteno", 'name' => 'de', 'tpe' => UHP::UHP_INPUT); 
        $r['cp3'][] = array("local" => "tdmaprot->framelen",  'name' => 'db', 'tpe' => UHP::UHP_INPUT); 
        $r['cp3'][] = array("local" => "tdmaprot->slotsize",  'name' => 'dc', 'tpe' => UHP::UHP_INPUT); 
        $r['cp3'][] = array("local" => "tdmaprot->stations",  'name' => 'dd', 'tpe' => UHP::UHP_INPUT); 
        $r['cp3'][] = array("local" => "tdmaprot->notcheck",  'name' => 'dg', 'tpe' => UHP::UHP_CHECKBOX); 

        // Crosspol
        $r['cx3'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_PROFILEID);
        $r['cx3'][] = array("local" => "crosspol->freq",      'name' => 'db', 'tpe' => UHP::UHP_INPUT); 
        $r['cx3'][] = array("local" => "crosspol->duration",  'name' => 'dc', 'tpe' => UHP::UHP_INPUT); 

        // TDMA BW request profiles
        $r['cj3'][] = array('local' => "id",                  'name' => 'da', 'tpe' => UHP::UHP_PROFILEID);
        $r['cj3'][] = array("local" => "tdmabw->active0",     'name' => 'db', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->idle0",       'name' => 'dc', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->down0",       'name' => 'dd', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->timeout0",    'name' => 'de', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->active1",     'name' => 'df', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->idle1",       'name' => 'dg', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->down1",       'name' => 'dh', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->timeout1",    'name' => 'di', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->active2",     'name' => 'dj', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->idle2",       'name' => 'dk', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->down2",       'name' => 'dl', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->timeout2",    'name' => 'dm', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->active3",     'name' => 'dn', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->idle3",       'name' => 'do', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->down3",       'name' => 'dp', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->timeout3",    'name' => 'dq', 'tpe' => UHP::UHP_INPUT); 
        $r['cj3'][] = array("local" => "tdmabw->rtreqcir",    'name' => 'dw', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cj3'][] = array("local" => "tdmabw->traffactive", 'name' => 'dx', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cj3'][] = array("local" => "tdmabw->hardmir",     'name' => 'dy', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cj3'][] = array("local" => "tdmabw->optimize",    'name' => 'dv', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['cj3'][] = array("local" => "tdmabw->reqscale",    'name' => 'dz', 'tpe' => UHP::UHP_INPUT); 

        return $r;
    }

    function qosPolicyRules()
    {
        $tpe_values =  array(1  => "802.1q priority (0-7)",
                             2  => "VLAN (0-4095)",
                             3  => "TOS (0-255)",
                             4  => "DSCP (0-63)",
                             5  => "Protocol (0-255)",
                             6  => "SRC Net",
                             7  => "DST Net",
                             8  => "SRC TCP port (0-65535)",
                             9  => "DST TCP port (0-65535)",
                             10 => "SRC UDP port (0-65535)",
                             11 => "DST UDP port (0-65535)",
                             12 => "ICMP type (0-255)",
                             17 => "Drop",
                             18 => "Set queue",
                             19 => "Set TS channel (0-680)",
                             20 => "No TCP acceleration",
                             21 => "Compress RTP headers",
                             22 => "No screening",                             
                             23 => "Set ACM channel (1-6)",
                             24 => "Set SYSTEM priority",
                             25 => "Drop if station down",
                             27 => "Set TOS (0-255)",
                             28 => "Set DSCP (0-63)",                             
                             29 => "GOTO policy (1-500)",
                             30 => "CALL policy (1-500)");
        $r['ci5'][] = array("local" => "checkaction",         'name' => 'dz', 'tpe' => UHP::UHP_RADIO, 'list' => $tpe_values); 
        $r['ci5'][] = array("local" => "invert",              'name' => 'dw', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['ci5'][] = array("local" => "actiononmatch",       'name' => 'dx', 'tpe' => UHP::UHP_CHECKBOX); 
        $r['ci5'][] = array("local" => "8021qpriority ",      'name' => 'dv', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "vlanmin",             'name' => 'dc', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "vlanmax",             'name' => 'dd', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "tosmin",              'name' => 'de', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "tosmax",              'name' => 'df', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "dscpmin",             'name' => 'dh', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "dscpmax",             'name' => 'di', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "protonum",            'name' => 'dg', 'tpe' => UHP::UHP_INPUT);         
        $r['ci5'][] = array("local" => "icmptype",            'name' => 'dr', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "srcnet",              'name' => 'ij', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "srcmask",             'name' => 'mk', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "dstnet",              'name' => 'in', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "dstmask",             'name' => 'mo', 'tpe' => UHP::UHP_INPUT);
        $r['ci5'][] = array("local" => "srctcpmin",           'name' => 'dl', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "srctcpmax",           'name' => 'dm', 'tpe' => UHP::UHP_INPUT);
        $r['ci5'][] = array("local" => "dsttcpmin",           'name' => 'dp', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "dsttcpmax",           'name' => 'dq', 'tpe' => UHP::UHP_INPUT);
        $r['ci5'][] = array("local" => "srcudpmin",           'name' => 'dA', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "srcudpmax",           'name' => 'dB', 'tpe' => UHP::UHP_INPUT);
        $r['ci5'][] = array("local" => "dstudpmin",           'name' => 'dC', 'tpe' => UHP::UHP_INPUT); 
        $r['ci5'][] = array("local" => "dstudpmax",           'name' => 'dD', 'tpe' => UHP::UHP_INPUT);
        $r['ci5'][] = array("local" => "queue",               'name' => 'ds', 'tpe' => UHP::UHP_SELECT, 'list' => array(0 => "P1(Low)", 1 => "P2", 2 => "P3", 3 => "P4(Med)", 4 => "P5", 5 => "P6", 7 => "P7(High)"));
        $r['ci5'][] = array("local" => "tschannel",           'name' => 'du', 'tpe' => UHP::UHP_SELECT);
        $r['ci5'][] = array("local" => "acmhannel",           'name' => 'dE', 'tpe' => UHP::UHP_SELECT, 'list' => array(0 => "1", 1 => "2", 2 => "3", 3 => "4", 4 => "5", 5 => "6"));
        $r['ci5'][] = array("local" => "tos",                 'name' => 'dH', 'tpe' => UHP::UHP_INPUT);
        $r['ci5'][] = array("local" => "gotopolicy",          'name' => 'dJ', 'tpe' => UHP::UHP_INPUT);
        $r['ci5'][] = array("local" => "dscp",                'name' => 'dI', 'tpe' => UHP::UHP_INPUT);
        $r['ci5'][] = array("local" => "callpolicy",          'name' => 'dK', 'tpe' => UHP::UHP_INPUT);
        return $r;
    }

    function flashLoadSaveRules()
    {
        $r['cc1'][] = array("local" => "cfgflashbank",       'name' => 'da', 'tpe' => UHP::UHP_SELECT, 'list' => array(0 => "Bank 0 (Main)", 1 => "Bank 1", 2 => "Factory Default")); 
        $r['cc1'][] = array("value" => "Save",               'name' => 'tc', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_SAVE);
        $r['cc1'][] = array("value" => "Load",               'name' => 'tc', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_LOAD);
        $r['cc1']['options'] = array('writeurl' => 'cw1'); 
        return $r;
    }

    function tftpLoadSaveRules()
    {
        $r['cc1'][] = array("local" => "tftpfile",           'name' => 'tb', 'tpe' => UHP::UHP_INPUT); 
        $r['cc1'][] = array("value" => "Save",               'name' => 'td', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_SAVE);
        $r['cc1'][] = array("value" => "Load",               'name' => 'td', 'tpe' => UHP::UHP_CONST, 'on' => UHP::UHP_ON_LOAD);    
        $r['cc1']['options'] = array('writeurl' => 'cw1');
        return $r;
    }


    function userAccessRules()
    {
        $r['cc20'][] = array("input" => "userpwd",            'name' => 'tb', 'tpe' => UHP::UHP_INPUT);
        $r['cc20'][] = array("input" => "adminpwd",           'name' => 'tc', 'tpe' => UHP::UHP_INPUT);    
        $r['cc20'][] = array("value" => "Apply",              'name' => 'tc', 'tpe' => UHP::UHP_CONST);
        $r['cc20']['options'] = array('writeurl' => 'cw20');
        return $r;
    }


    function ipScreeningRules()
    {
        $r['cc13'][] = array("input" => "ipscreening",        'name' => 'dc', 'tpe' => UHP::UHP_SELECT, 'list' => array(1 => 'AUTO', 1 => "ON", 2 => "OFF"));    
        $r['cc13'][] = array("value" => "Apply",              'name' => 'td', 'tpe' => UHP::UHP_CONST);
        $r['cc13']['options'] = array('writeurl' => 'cw13');
        return $r;
    }

    function networkScriptRules()
    {
        $r['cc42'][] = array("local" => "type",               'name' => 'db', 'tpe' => UHP::UHP_RADIO, 'list' => array(0 => "Local", 1 => "S/N", 2 => "Broadcast"));   
        $r['cc42'][] = array("local" => "sn",                 'name' => 'dc', 'tpe' => UHP::UHP_INPUT);   
        $r['cc42'][] = array("local" => "cmd",                'name' => 'aa', 'tpe' => UHP::UHP_INPUT);   
        $r['cc13'][] = array("value" => "Apply",              'name' => 'ta', 'tpe' => UHP::UHP_CONST);
        return $r;
    }


    function ripRules()
    {
        $r['cc10'][] = array("local" => "enable",             'name' => 'db', 'tpe' => UHP::UHP_CHECKBOX);   
        $r['cc10'][] = array("local" => "gwip",               'name' => 'ia', 'tpe' => UHP::UHP_INPUT);   
        $r['cc10'][] = array("local" => "omitdownsta",        'name' => 'dc', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cc10'][] = array("local" => "couplesmalarms",     'name' => 'dd', 'tpe' => UHP::UHP_CHECKBOX);
        $r['cc10'][] = array("local" => "cost",               'name' => 'de', 'tpe' => UHP::UHP_INPUT);   
        $r['cc10'][] = array("value" => "Apply",              'name' => 'ta', 'tpe' => UHP::UHP_CONST,  'on' => UHP::UHP_ON_APPLY);
        return $r;
    }


    function netPageRules()
    {
        $r['url']    = "ss37";
        $r['regexp'] = '/[\s.|\S]+Mode:(.*)State:(.*)[\s.|\S]+Inroute:(.*)[\s.|\S]+RxFrq:(.*)SR:(.*)FEC:(.*)10M:(.*)RO:(.*)[\s.|\S]+AcqBw:(.*)DataBw:(.*)[\s.|\S]+SlLen:(.*)FrLen:(.*)StNum:(.*)ActChannels:(.*)[\s.|\S]+BitR:(.*)SlDur:(.*)FrDur:(.*)SlotBw:(.*)[\s.|\S]+InLvl:(.*)RXoff:(.*)NoBurst:(.*)ZeroSt:(.*)FpLost:(.*)[\s.|\S]+EnaSt:(.*)UpSt:(.*)ActSt:(.*)[\s.|\S]+TotRq:(.*)RtRq:(.*)GuaRq:(.*)RqSl:(.*)Load:(.*)[\s.|\S]+HubRefL:(.*)LowSt:(.*)HighSt:(.*)[\s.|\S]+RemRefL:(.*)LowSt:(.*)HighSt:(.*)[\s.|\S]+Mode:(.*)NetTTS:(.*)TCL:(.*)Errors:(.*)[\s.|\S]+SatPos:(.*)GpsPkts:(.*)[\s.|\S]+Set\s\slocation:(.*)Set.*TTS=(.*)[\s.|\S]+location:(.*)Used.*TTS=(.*)/';
        $r['keys']   = array(0  => "state->mode",
                             1  => "state->state",
                             2  => "ids->inroute",
                             3  => "tdmarf->rcfreq",
                             4  => "tdmarf->sr",
                             5  => "tdmarf->fec",
                             6  => "tdmarf->10m",
                             7  => "tdmarf->ro",
                             8  => "tdmarf->acqbw",
                             9  => "tdmarf->databw",
                             11 => "tdmaproto->sllen",
                             12 => "tdmaproto->frlen",
                             13 => "tdmaproto->stnum",
                             14 => "tdmaproto->actchannels",
                             15 => "tdmacalc->bitr",
                             16 => "tdmacalc->sldur",
                             17 => "tdmacalc->frdur",
                             18 => "tdmacalc->slotbw",
                             19 => "burstdemod->inlvl",
                             20 => "burstdemod->rxoff",
                             21 => "burstdemod->noburst",
                             22 => "burstdemod->zerost",
                             23 => "burstdemod->fplost",
                             24 => "stations->enabled",
                             25 => "stations->up",
                             26 => "stations->active",
                             27 => "load->totrq",
                             28 => "load->rtrq",
                             29 => "load->guarq",
                             30 => "load->rqsl",
                             31 => "load->load",
                             32 => "tlc->hubrefl",
                             33 => "tlc->hubstl",
                             34 => "tlc->hubhighst",
                             35 => "tlc->remrefl",
                             36 => "tlc->remlowst",
                             37 => "tlc->remhighst",
                             38 => "timing->mode",
                             39 => "timing->nettts",
                             40 => "timing->tcl",
                             41 => "timing->errors",
                             42 => "timing->satpos",
                             43 => "timing->gpspkts",
                             44 => "timing->setlocation",
                             45 => "timing->settts",
                             46 => "timing->usedlocation",
                             47 => "timing->usedtts");
        return $r;
    }

    function modulatorPageRules()
    {
        $r['url']    = "ss32";
        $r['regexp'] = '/[\s.|\S]+interface\sis\s(.*)[\s.|\S]+Last\sU->D:(.*)U->D\stransitions:(.*)[\s.|\S]+D->U:(.*)Cou.*cleared:(.*)[\s.|\S]+Freq:(.*)FreqAdj:(.*)SR:(.*)SetLvl:(.*)Max:(.*)10M:(.*)[\s.|\S]+LO:(.*)FixCorr:(.*)BR:(.*)OutLvl:(.*)TX:(.*)24V:(.*)[\s.|\S]+Mode:(.*)Modulation:(.*)FEC:(.*)Rolloff:(.*)Pilots:(.*)[\s.|\S]+Rate\/bps:(.*)Shaper_drops:(.*)[\s.|\S]+/';
        $r['keys']   = array(0  => "status",
                             1  => "lastUD",
                             2  => "UDtransitions",
                             3  => "lastDU",
                             4  => "counters_cleared",
                             5  => "freq",
                             6  => "freqadj",
                             7  => "sr",
                             8  => "setlvl",
                             9  => "max",
                             10 => "10m",
                             11 => "lo",
                             12 => "fixcorr",
                             13 => "br",
                             14 => "outlvl",
                             15 => "tx",
                             16 => "24v",
                             17 => "mode",
                             18 => "mod",
                             19 => "fec",
                             20 => "rolloff",
                             21 => "pilots",
                             22 => "ratebps",
                             23 => "shaper_drops");
        return $r;
        
    }

    function demodulator1PageRules()
    {   
        $r['url']    = "ss33";
        $r['regexp'] = '/[\s.|\S]+is(.*)\nLast\sU->D:(.*)U->D\stransitions:(.*)\n.*D->U:(.*)Counters\scleared:(.*)\n[\s.|\S]+LNB-pwr:(.*)T10M:(.*)Offset:(.*)KHz.*SearchBW:(.*)KHz[\s.|\S]+LO:(.*)Frq:(.*)SR:(.*)Mode:(.*)SpI:(.*)\s\|[\s.|\S]+FixOffset[\s.|\S]+(.*)\|(.*)\|(.*)\|(.*)\|(.*)\|(.*)\|(.*)\|[\s.|\S]+Rate\/bps:(.*)[\s.|\S]+Packets:(.*)Bytes:(.*)CRC_errors:(.*)[\s.|\S]+Packets:(.*)Bytes:(.*)CRC_errors:(.*)[\s.|\S]+Packets:(.*)Bytes:(.*)CRC_errors:(.*)[\s.|\S]+Packets:(.*)Bytes:(.*)CRC_errors:(.*)[\s.|\S]+Packets:(.*)Bytes:(.*)CRC_errors:(.*)[\s.|\S]+Packets:(.*)Bytes:(.*)CRC_errors:(.*)[\s.|\S]+/';
        $r['keys']   = array(0  => "state",
                             1  => "lastUD",
                             2  => "UDtransitions",
                             3  => "lastDU",
                             4  => "counter_cleared",
                             5  => "outdoor->lnb_pwr",
                             6  => "outdoor->t10m",
                             7  => "outdoor->lnb_offset",
                             8  => "outdoor->searchbw",
                             9  => "outdoor->lnb_lo",
                             10 => "outdoor->freq",
                             11 => "outdoor->sr",
                             12 => "outdoor->mode",
                             13 => "outdoor->spi",
                             14 => "demostate->inlvl",
                             15 => "demostate->spi",
                             16 => "demostate->mod",
                             17 => "demostate->fec",
                             18 => "demostate->sroff",
                             19 => "demostate->cn",
                             20 => "demostate->rx_offset",
                             21 => "demostate->fix_offset",
                             22 => "rate_bps",
                             23 => "packets1",
                             24 => "bytes1",
                             25 => "crc_errors1",
                             26 => "packets2",
                             27 => "bytes2",
                             28 => "crc_errors2",
                             29 => "packets3",
                             30 => "bytes3",
                             31 => "crc_errors3",
                             32 => "packets4",
                             33 => "bytes4",
                             34 => "crc_errors4",
                             35 => "packets5",
                             36 => "bytes5",
                             37 => "crc_errors5",
                             38 => "packets6",
                             39 => "bytes6",
                             40 => "crc_errors6"
                    );
        return $r;

    }


    function systemPageRules()
    {
        // /m'; //
        $r['url']    = "ss35";
        $r['regexp'] = '/(.*)Ver:(.*)SN:(.*)\nUptime:(.*)CurrentTime:(.*)TimeZone:(.*)\nRateAvgTime:(.*)BuffersFree:(.*)NoBuffer:(.*)ScDesc:(.*)\nCPUload:(.*)IdleTimeout:(.*)Temperature:(.*)\nLastTelnetIP:(.*)AutoRestartDelay:\s(\d+)[\s.|\S]+Key\s0.*In\skey:\s+(\d+)\s+(\d+)\s+(\d+)\s.*key:\s+(\d+)\s+(\d+)\s+(\d+)[\s.|\S]+Options:(.*)\n+Key\s1.*In\skey:\s+(\d+)\s+(\d+)\s+(\d+).*key:\s+(\d+)\s+(\d+)\s+(\d+)[\s.|\S]+Lock\sID1:(.*)[\s.|\S]+Key\s2.*In\skey:\s+(\d+)\s+(\d+)\s+(\d+).*key:\s+(\d+)\s+(\d+)\s+(\d+)[\s.|\S]+Lock\sID2:(.*)\n[\s.|\S]+Errors\sreport:((.|\n)*)/s';        
        $r['keys']   = array( 0 => "swtype", 
                              1 => "swver",
                              2 => "sn",
                              3 => "uptime",
                              4 => "currenttime",
                              5 => "timezone",
                              6 => "rateavgtime",
                              7 => "buffersfree",
                              8 => "nobuffer",
                              9 => "scdesc", 
                              10 => "cpuload",
                              11 => "idletimeout",
                              12 => "temperature",
                              13 => "lasttelnetip",
                              14 => "autorestartdelay", 
                              15 => "inkey00",
                              16 => "inkey01",
                              17 => "inkey02",
                              18 => "outkey00",
                              19 => "outkey01",
                              20 => "outkey02",
                              21 => "options",
                              22 => "inkey10",
                              23 => "inkey11",
                              24 => "inkey12",
                              25 => "outkey10",
                              26 => "outkey11",
                              27 => "outkey12",
                              28 => "lockid1",
                              29 => "inkey20",
                              30 => "inkey21",
                              31 => "inkey22",
                              32 => "outkey20",
                              33 => "outkey21",
                              34 => "outkey22",
                              35 => "lockid2",
                              36 => "errors"
    );
        return $r;
    }
    

}


?>
