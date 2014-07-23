<?php
include "system/config.php";
include "system/machine/func.php";

//GET CURENT URL
function curPageURL() {
$pageURL = 'http';
if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
$pageURL .= "://";
if (@$_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
} else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}
return $pageURL;
} 

//GET CURENT URL PAGE NAME
function curPageName() {
return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

//INDEX LOAD
function index($filename)
{ 
	$x = 0;
	$url = str_replace(page(),"",curPageURL());
	$url = str_replace(".exe","",$url);
	$sub_url = explode("/",@$url);
	if(@$sub_url[0]=="")
	{
		$status = @include("controller/".$filename);
		if (!$status)
		{
			echo "<style>body{background-color:#808080;}</style><br><br><br><div align='center'><img src='".page()."images/404.jpg'</div>";
		}
	}
	else
	{
		$status = @include("controller/".$sub_url[0].".php");
		if (!$status)
		{
			echo "<style>body{background-color:#808080;}</style><br<br><br><div align='center'><img src='".page()."images/404.jpg'</div>";
			
		}
	}
	

	
}
function url(){
    return sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['HTTP_HOST'],
    $_SERVER['REQUEST_URI']
  );
   }
   
function get($i)
{
$url = str_replace(page(),"",curPageURL());
$url = str_replace(".exe","",$url);
$sub_url = explode("/",@$url);
return $sub_url[$i];	
}

function getTemplate($html)
{
ob_start();
include_once( 'html/'.$html.".html" );
$html = ob_get_contents();
ob_end_clean(); 
$html = str_replace('[url]',page(),$html);
return $html;
}

function getSlcTmp($path)
{
ob_start();
include_once( 'html/'.$path.".html" );
$path = ob_get_contents();
ob_end_clean(); 
$path = str_replace('[url]',page(),$path);
$slicePage = explode("[systemCut]",$path);
return $slicePage;
}


function timePlusPlus($datetime,$jam_plus,$menit_plus,$detik_plus,$bulan_plus,$hari_plus,$tahun_plus)
{
$tanggal = explode("-",substr($datetime,0,10));
$thn = $tanggal[0];
$bln = $tanggal[1];
$tgl= $tanggal[2];
$waktu = explode(":",substr($datetime,11,8));
$jam = $waktu[0];
$menit = $waktu[1];
$detik= $waktu[2];
//return $thn."#".$bln."#".$tgl."#".$jam."#".$menit."#".$detik;
$hasil = mktime($jam + $jam_plus,$menit + $menit_plus,$detik + $detik_plus,$bln + $bulan_plus,$tgl + $hari_plus,$thn + $tahun_plus);
$hasilx = date("Y-m-d H:i:s", $hasil);
return $hasilx;
}

function sqltotgl($mentah) {
$pecah = explode("-",$mentah);
$tanggal = $pecah[1]."/".$pecah[2]."/".$pecah[0];
return $tanggal;
}
function tgltosql($mentah) {
$pecah = explode("/",$mentah);
$tanggal = $pecah[2]."-".$pecah[0]."-".$pecah[1];
return $tanggal;
}

function redirect($url)
{
	echo '<script>window.location = "'.$url.'"</script>';	
}

if(substr(curPageURL(),"0","10") == "http://www")
{
	redirect(str_replace("www.","",curPageURL()));
}


function slug($title)
{
	return strtolower(str_replace(" ","-",$title));
}
function post($variable)
{
return $_POST[$variable];
}

function postAman($data)
{
	$hasil = mysql_real_escape_string($_POST[$data]);
	
	return $hasil;
}

function Aman($data)
{
	$hasil = mysql_real_escape_string($data);
	
	return $hasil;
}

function Go($functionName)
{
$function = $_POST[$functionName];
if($function!="")
{
return $function();
}
else
{
	//do nathing
}
}

function valGo($functionName)
{
$function = $functionName;
if($function!="")
{
return $function();
}
else
{
	//do nathing
}
}

function show($variable)
{
	echo $variable;
}


function sts($string)
{
	//strip to space
	$hasil = str_replace("-"," ",$string);
	return $hasil;
}


function setPokes($key,$value)
{
setcookie($key,$value,time()+3600);
}

function delPokes($key)
{
setcookie($key, "", time()-3600);
}

function showPokes($key)
{
return $_COOKIE[$key];
}

function database($setting)
{
	$host = $setting['host'];
	$user = $setting['user'];
	$pass = $setting['password'];
	$db = $setting['database'];
	$koneksi=@mysql_connect($host,$user,$pass );
	$koneksidb=@mysql_select_db($db,$koneksi);
	return $koneksidb;
}

function tanggal($format,$nilai){
	//keluaran Minggu, 13 Maret 2011
	//tanggal("D, j M Y" h:i:s);
$en=array("Sun","Mon","Tue","Wed","Thu","Fri","Sat","Jan","Feb",
"Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
$id=array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu",
"Jan","Feb","Maret","April","Mei","Juni","Juli","Agustus","September",
"Oktober","November","Desember");
return str_replace($en,$id,date($format,strtotime($nilai)));
}

function ip() {
     $ipaddress = '';
     if ($_SERVER['HTTP_CLIENT_IP'])
         $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
     else if($_SERVER['HTTP_X_FORWARDED_FOR'])
         $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
     else if($_SERVER['HTTP_X_FORWARDED'])
         $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
     else if($_SERVER['HTTP_FORWARDED_FOR'])
         $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
     else if($_SERVER['HTTP_FORWARDED'])
         $ipaddress = $_SERVER['HTTP_FORWARDED'];
     else if($_SERVER['REMOTE_ADDR'])
         $ipaddress = $_SERVER['REMOTE_ADDR'];
     else
         $ipaddress = 'UNKNOWN';

     return $ipaddress; 
}

function idtoInvoice($id)
{
	$min = 4;
	if(strlen($id)<$min)
	{
		for($j = 0; $j < $min-strlen($id); $j++)
		{
		$extraLen = $extraLen."0";
		}
	}
	else 
	{
		$extraLen = "";
	}

	return $extraLen.$id;
	
}

function digit($data,$digit)
{
	$min = $digit;
	if(strlen($id)<$min)
	{
		for($j = 0; $j < $min-strlen($data); $j++)
		{
		$extraLen = $extraLen."0";
		}
	}
	else 
	{
		$extraLen = "";
	}

	return $extraLen.$data;
	
}

function rp($duit)
{
return 'Rp. ' . number_format( $duit, 0 , '' , '.' ) . ',00';	
}

function duit($duit)
{
return number_format( $duit, 0 , '' , '.' );	
}

function sendMail($judul,$kepada,$emailTo,$emailFrom,$isiHTML)
{
//kirim Emal
	// multiple recipients
$to  = $email;

// subject
$subject =  $judul;

// message

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'To: '.$kepada.' <'.$emailTo.'>' . "\r\n";
$headers .= 'From:  Janmatour <'.$emailFrom.'>' . "\r\n";
//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
// Mail it
mail($to, $subject, $content, $headers);
}

function MultiSendMail($judul,$kepada,$emailTo,$emailFrom,$isiHTML)
{
//kirim Emal
	// multiple recipients
$to  = $email;

// subject
$subject =  $judul;

// message

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'To: '.$emailTo.''. "\r\n";
$headers .= 'From:  Janmatour <'.$emailFrom.'>' . "\r\n";
//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
// Mail it
mail($to, $subject, $content, $headers);
}

function baca($alamat)
{
$my_file = $alamat;
$handle = fopen($my_file, 'r');
$data = fread($handle,filesize($my_file)); //read
return $data;
}

function tulisfile($alamat,$data)
{
$my_file = $alamat;
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
fwrite($handle, $data); //write in file
}

function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}


function scan_Dir($dir) {
    $arrfiles = array();
    if (is_dir($dir)) {
        if ($handle = opendir($dir)) {
            chdir($dir);
            while (false !== ($file = readdir($handle))) { 
                if ($file != "." && $file != "..") { 
                    if (is_dir($file)) { 
                        $arr = scan_Dir($file);
                        foreach ($arr as $value) {
                            $arrfiles[] = $dir."/".$value;
                        }
                    } else {
                        $arrfiles[] = $dir."/".$file;
                    }
                }
            }
            chdir("../");
        }
        closedir($handle);
    }
    return $arrfiles;
}

function SuperDownload($path)
{
$file = $path;

	if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    
	}	
}


function formatBytes($size, $precision = 2)
{
$base = log($size) / log(1024);
$suffixes = array('', ' kB', ' MB', ' GB', ' TB');
return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}

function pangkat($bil,$pangkat)
{
	$hasil = exp ($pangkat * log($bil));
	return $hasil;
}

function last_str($str)
{
	$panjang = strlen($str)-1;
	return substr($str,$panjang,1);
}


function copyRemoteFile($url, $localPathname){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    if ($data) {
        $fp = fopen($localPathname, 'wb');

        if ($fp) {
            fwrite($fp, $data);
            fclose($fp);
        } else {
            fclose($fp);
            return false;
        }
    } else {
        return false;
    }

    return true;
}
?>
