<?php

class Http{
  public function init($file){
        $body=array();
        $this->const=array("Host","Connection",'Cookie','path','params',"method");
        $headers=explode("\n",$file);
        $this->postfield=explode("\n\n",$file)[1];
        foreach($headers as $header){

          if ($header!==""){

            if (sizeof(explode(":",$header))==2){
              if (in_array(explode(":",$header)[0],$this->const)){
            $body[explode(":",$header)[0]]=explode(":",$header)[1];}else{
              $body[explode(":",$header)[0]]='formatted';
            }
          }
          else{


            if (strpos($header,"GET /")!==false){
              $this->method="GET";
              $this->path=explode("?",str_replace(" ","",str_replace("HTTP/1.1","",str_replace($this->method,"",$header))))[0];
              $params=str_replace($this->path,"",str_replace(" HTTP/1.1","",str_replace("GET ","",$header)));
              $this->params="";
              foreach(explode("&",$params) as $param){
                
                  $this->params.=explode("=",$param)[0]."=formatted&";
                
              }
            }if(strpos($header,"POST /")!==false){$this->method="POST";
              $params=explode("\n\n",$file)[1];
              $this->params="";
              foreach(explode("&",$params) as $param){
                
                  $this->params.=explode("=",$param)[0]."=formatted&";
                
              }
              $this->path=explode("?",str_replace(" ","",str_replace("HTTP/1.1","",str_replace($this->method,"",$header))))[0];
              }else{
              $this->postfield=$header;
            }

        }

        $body['Connection']="Close";
        $body["params"]=str_replace("\n","",$this->params);
        $body['method']=$this->method;
        $body=array_reverse($body);
        $this->body=$body;

        }
        //print_r($additional);
      }}
      public function getcookies(){
          if(isset($this->body['Cookie'])){

            $cookies=array();
            $all=explode(";",$this->body["Cookie"]);
            foreach($all as $t){
              array_push($cookies,explode('=',$t)[0].'=formatted');
            }
            $this->cookies=$cookies;
            $this->body['Cookie']=implode(";",$cookies);
          }else{
            $this->body['Cookie']='PHPSESSID:formatted';
          }
      }
      
      function generate(){
        $header="";
        if ($this->body['method']=='GET'){
          $header.="GET ".$this->path."?".$this->body['params']." HTTP/1.1\r\n"."Host: ".$this->body['Host']."\r\n"."Cookie: ".str_replace(" ","",$this->body['Cookie'])."\r\n"."Connection: Close\r\n";
          foreach($this->body as $key=>$value){
            if(!in_array($key,$this->const)){
              $header.="$key: $value\r\n";
            }
            
            
          }
          $header.="\r\n\r\n";
            if(isset($this->postfield)){
              $header.="formatted";
            }
            $this->payload=$header;
        }else{
          $header.=$this->method." ".$this->path." HTTP/1.1\r\n"."Host: ".$this->body['Host']."\r\n"."Cookie: ".str_replace(" ","",$this->body['Cookie'])."\r\n"."Connection: Close\r\n";
          foreach($this->body as $key=>$value){
            if(!in_array($key,$this->const)){
              $header.="$key: $value\r\n";
            }
            
            
          }
          $header.="\r\n\r\n";
            if(isset($this->postfield)){
              $header.=$this->params;
            }
            $this->payload=$header;
        }
      }
}










  ?>
