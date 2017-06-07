@echo off
mshta vbscript:createobject("wscript.shell").run("""iexplore"" http://192.168.1.104/crontab/index.php?act=minutes",0)(window.close) 
@echo off
mshta vbscript:createobject("wscript.shell").run("""iexplore"" http://192.168.1.104/crontab/index.php?act=hour",0)(window.close) 
@echo off
mshta vbscript:createobject("wscript.shell").run("""iexplore"" http://192.168.1.104/crontab/index.php?act=date",0)(window.close) 
@echo off
mshta vbscript:createobject("wscript.shell").run("""iexplore"" http://192.168.1.104/crontab/index.php?act=month",0)(window.close) 
echo 1
	taskkill /f /im iexplore.exe 