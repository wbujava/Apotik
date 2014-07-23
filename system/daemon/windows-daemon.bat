@echo off
:START
REM -------------------------------------------------------------------------------
REM This bat-file daemon for execute PHP file
REM Written by Akhyar Maulana
REM 08/09/2003
REM -------------------------------------------------------------------------------




		START D:\xamppNew\php\php-win.exe "D:\xamppNew\htdocs\box\controller\cronschedule.php"
REM -------------------------------------------------------------------------------
REM                            ^                        ^
REM SET PHP Application file   |                        |  
REM SET PHP FILE LOCATION HERE                          |
REM -------------------------------------------------------------------------------

echo file executed
					timeout /t 1
REM -------------------------------------------------------------------------------
REM                            ^                        
REM SET time out  (second)	   |     if you want to run trough just set 0     
REM -------------------------------------------------------------------------------
GOTO START
