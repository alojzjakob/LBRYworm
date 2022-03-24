<?php

// https://github.com/lbryio/chainquery/blob/master/db/chainquery_schema.sql

// array(2) { ["header"]=> string(211) "HTTP/2 502 cache-control: max-age=180 content-type: text/plain; charset=utf-8 server: Caddy x-cache-status: miss x-content-type-options: nosniff content-length: 32 date: Mon, 19 Oct 2020 10:10:30 GMT " ["body"]=> string(32) "502 Bad Gateway 502 Bad Gateway " }

//https://chainquery.lbry.com/api/sql?query=SELECT%20name,title%20FROM%20claim%20WHERE%20content_type=%22application/pdf%22%20AND%20(%20title%20LIKE%20%22%asterix%%22%20OR%20name%20LIKE%20%22%asterix%%22)%20%20AND%20bid_state%3C%3E%22Spent%22%20AND%20claim_type%3E0%20LIMIT%2010

// epub example
// https://chainquery.lbry.com/api/sql?query=select%20*%20from%20claim%20where%20claim_id=%27936e9152d66b355227e8d68653b158d036395095%27
// https://odysee.com/@mime:6/epub:9

// file types
//https://github.com/lbryio/lbry-sdk/blob/a1abd94387199f753bfc1ead600c702d21a0d683/lbry/schema/mime_types.py

class ChainQuery{

    public $LBRYworm;
    public $chainquery_url='https://chainquery.lbry.com/api/sql';
    //public $chainquery_url='https://chainquery.odysee.tv/api/sql';
    public $lighthouse_url='https://lighthouse.odysee.tv/search';
    public $odysee_proxy_url='https://api.na-backend.odysee.com/api/v1/proxy';
        
    const CHANNEL_CONTENT_TIPS_PER_DAY = 1;
    const PUBLISHES_TIPS_PER_DAY = 2;
    const CHANNEL_TIPS_PER_DAY = 3;
    
    public function __construct(&$lw){
        $this->LBRYworm=$lw;
    }
    
    public function get_claim_streaming_url($uri){
        
        //$uri='lbry://'.$channel['channel']['name'].':'.$channel['channel']['claim_id'].'/'.$random['name'].':'.$random['claim_id'];
        
        $method='?m=get';
        $url=$this->odysee_proxy_url.$method;
        /*
        {
            "jsonrpc": "2.0",
            "method": "get",
            "params": {
                "uri": "lbry://@Alojz:f/Odysee-Live-Stream-Chat-Overlay:9",
                "save_file": false
            },
            "id": 1637948988802
        }
        */
        $data=array(
                        'jsonrpc'=>'2.0',
                        'id'=>1,
                        'method'=>'get',
                        'params'=>array(
                            'uri'=>$uri,
                            'save_file'=>false,
                        ),
                    );
        $resp=$this->LBRYworm->curl($url,'POST',$data,true);
        if($resp['body']!==''){
            //var_dump($resp);
            $res=json_decode($resp['body'],true);
            return $res['result']['streaming_url'];
        }else{
            usleep(200000);// 200 milliseconds
            return $this->get_claim_streaming_url($uri);
        }
    }
    
    public function search($query,$content_type=array('"application/pdf"','"application/epub+zip"','"application/vnd.comicbook-rar"','"application/vnd.comicbook+zip"')){
        //$req_data=array('query' => 'SELECT name,title FROM claim WHERE content_type="'.$content_type.'" AND ( title LIKE "%'.$query.'%" OR name LIKE "%'.$query.'%")  AND bid_state<>"Spent" AND claim_type>0 LIMIT 10');
        
        
        if(strlen($query)<3){
            return array('data'=>null,'error'=>'Search query must contain at least 3 characters...');
        }
        
        $params_arr=array();
        $param_arr=explode(' ',$query);
        foreach($param_arr as $param){
            $params_arr[]='title LIKE "%'.$param.'%"';
            //$params_arr[]='(?=.*'.$param.')';
            
            //this works
            //$params_arr[]='('.$param.')';
            
            //$params_arr[]='(?=.*\\'.$param.'\\b)';
            //$params_arr[]='('.$param.')';
            //$params_arr[]=$param;
        }
        $params=implode(' AND ',$params_arr);
        //$params=implode('|',$params_arr);
        //$params='^.*'.implode('',$params_arr).'.*$';
        //$params='^.*('.implode('',$params_arr).').*$';
        //$params='^.*('.implode('|',$params_arr).').*$';
        
        // this works but slow
        //$params='^.*'.implode('',$params_arr).'.*$';
        
        //f6ac161e5f4100b6bb22544460b7dfc2af9b1ec3 memory of the world
        
        //https://lighthouse.odysee.tv/search?s=hamlet&size=5&from=0&nsfw=false
        //https://lighthouse.odysee.tv/search?s=hamlet&size=5&from=0&nsfw=false&claimType=file&mediaType=application
        //claimType: file
        //mediaType: audio,video,text
        
        $from=$_POST['from']??0;
        $count=$_POST['count']??20;
        
        $req_data=array(
                        's'=>$query,
                        'size'=>$count,
                        'from'=>$from,
                        'nsfw'=>false,
                        'claimType'=>'file',
                        'mediaType'=>'application'
                    );
        
        $raw_resp=$this->LBRYworm->curl($this->lighthouse_url,'GET',$req_data);
        $resp=json_decode($raw_resp['body'],true);
        
        //pre_var_dump($resp);
        
        $claim_ids=array();
        
        if(!isset($resp['error'])){
            foreach($resp as $r){
                $claim_ids[]='"'.$r['claimId'].'"';
            }
        }else{
            //return array('data'=>null,'error'=>$resp['error']);
        }
        
        //pre_var_dump($claim_ids);
        
        if(count($claim_ids)==0){
            return array('data'=>null);
        }
        
        //lbry://@Odysee#8/freedomofthepress#0

        $req_data=array('query' => 'SELECT name,title,claim_id,publisher_id,thumbnail_url,description,content_type FROM claim WHERE content_type IN ('.implode(',',$content_type).') AND claim_id IN ('.implode(',',$claim_ids).')  AND bid_state<>"Spent" ORDER BY FIELD(claim_id, '.implode(',',$claim_ids).')');
        
        //$req_data=array('query' => 'SELECT name,title,claim_id,thumbnail_url,description FROM claim WHERE content_type="'.$content_type.'" AND ( '.$params.' )  AND bid_state<>"Spent" AND claim_type>0 AND publisher_id IN("f6ac161e5f4100b6bb22544460b7dfc2af9b1ec3") ORDER BY id DESC LIMIT 10');
        
        // with above params and RLIKE
        //$req_data=array('query' => 'SELECT name,title,claim_id,thumbnail_url,description FROM claim WHERE content_type="'.$content_type.'" AND ( title RLIKE "'.$params.'" )  AND bid_state<>"Spent" AND claim_type>0 ORDER BY id DESC LIMIT 10');
        
        //$req_data=array('query' => 'SELECT * FROM claim WHERE content_type="'.$content_type.'" AND ( title LIKE "%'.$query.'%")  AND bid_state<>"Spent" AND claim_type>0 LIMIT 10');
        
        // default!
        //$req_data=array('query' => 'SELECT name,title,claim_id,thumbnail_url,description FROM claim WHERE content_type="'.$content_type.'" AND ( title LIKE "%'.$query.'%" )  AND bid_state<>"Spent" AND claim_type>0 ORDER BY id DESC LIMIT 10');
        
        //$req_data=array('query' => 'SELECT name,title,claim_id,thumbnail_url,description FROM claim WHERE content_type="'.$content_type.'" AND MATCH(title) AGAINST("'.$query.'" IN NATURAL LANGUAGE MODE)  AND bid_state<>"Spent" AND claim_type>0 ORDER BY id DESC LIMIT 10');
        
        //var_dump($req_data['query']);
        
        $raw_resp=$this->LBRYworm->curl($this->chainquery_url,'GET',$req_data);
        $resp=json_decode($raw_resp['body'],true);
        //var_dump($claim_resp);
        
        
        $channel_claim_ids=array();
        foreach($resp['data'] as $index=>$c){
            $channel_claim_ids[]='"'.$c['publisher_id'].'"';
            $resp['data'][$index]['description']=wpautop($resp['data'][$index]['description']);
        }
        $req_data=array('query' => 'SELECT name,title,claim_id,thumbnail_url,description FROM claim WHERE claim_id IN ('.implode(',',$channel_claim_ids).')  AND bid_state<>"Spent"');
        
        $resp['channels']=array();
        $req_channels=$this->LBRYworm->curl($this->chainquery_url,'GET',$req_data);
        //var_dump($req_channels);
        $resp_channels=json_decode($req_channels['body'],true);
        //var_dump($resp_channels);
        foreach($resp_channels['data'] as $c){
            $resp['channels'][$c['claim_id']]=$c;
        }
        
        return $resp;
        
        /*if($resp['data']===null){
            return null;
        }
        if(count($claim['data'])>0){
            //return $claim['data'][0];
            return $claim['data'];
        }else{
            return false;
        }*/
    }
    
    
    public function get_claim($claim_id){
        
        $req_data=array('query' => 'SELECT name,title,claim_id,publisher_id,thumbnail_url,description,content_type FROM claim WHERE claim_id="'.$claim_id.'" AND bid_state<>"Spent"');
        
        
        $raw_resp=$this->LBRYworm->curl($this->chainquery_url,'GET',$req_data);
        $resp=json_decode($raw_resp['body'],true);
        
        return $resp['data'][0];
        

    }
    
    
    
    
}
