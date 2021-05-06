<?php
#############################################################################################################################

error_reporting(0);set_time_limit(0);ini_set("memory_limit","-1");
ini_set('user_agent', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1;)');//UA設定

error_reporting(0);set_time_limit(0);ini_set("memory_limit","-1");
ini_set('user_agent', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1;)');//UA設定

#############################################################################################################################

$starttime=time();

//最終ログファイルのIDの取得
$url="https://zinro.net/m/room_list.php?scene=終了";
$data=file($url);
foreach($data as $x){
if(mb_ereg("confirmDialog",$x)){
$y=explode("'",$x);
$z=explode("=",$y[5]);
$shuryo=$z[1]*1;
break;}}

//取得済ファイルの最終IDを取得し、取得開始すべきIDを取得
$i=0;
$file=fopen("webdata.txt","r");
if($file){while($x=fgets($file)){$i++;}}
$i++;

#############################################################################################################################

for($k=0;$k<500000;$k++){

if($i>$shuryo){echo ($i-1)."までの".$k."個のログを取得しました";break;}

$url="https://zinro.net/m/log.php?id=".$i;

$data=file($url);

if(count($data)<5){echo "NO Data:".$i;exit;}

$namestat=array();$count=$flag=$j=0;foreach($data as $x){$j++;

//vilagename
if($j==51){$villagename=strip_tags(trim($x));}

//comment
if($j==120){$comment=str_replace(array("    var message = ",";"),"",$x);}

//common
$y=explode("=",$x);
$z=explode(",",$x);
$a=explode('"',$x);

//date
if($y[0]=="    var message "){
$date=explode(":",$z[6]);
$date=str_replace('"',"",$date[1]);}
//if($date==""){continue;}

//namestat
if($a[3]=="table table-striped table-bordered tbl"){$flag=1;}
if($a[1]=="footer"){$flag=0;}
if($flag>0){
if($a[0]=="                            <span style="){$count++;$namestat[$count][0]=str_replace(array("                            ","\r","\r\n","\n"),"",trim(strip_tags($x)));if($a[1]=="color:red"){$namestat[$count][2]=0;}else{$namestat[$count][2]=1;}}else{
$namestat[$count][1].=str_replace(array("                            ","\r","\r\n","\n"),"",trim(strip_tags($x)));
}}}

#############################################################################################################################

//書き込み

$reg=$i."<>".$villagename."<>".$date."<>".$comment."<>".json_encode($namestat)."<>";
$reg=str_replace(array("\r\n","\r","\n"),"",$reg);
$reg=$reg."\n";

//echo $reg;

file_put_contents("webdata.txt",$reg,FILE_APPEND | LOCK_EX);
sleep(1);
$i++;


}

fclose($file);

#############################################################################################################################

echo "人狼オンラインの最終ログID：".$shuryo."<BR>";
echo "取得済ログファイルの最終ID：".$i."<BR>";


$stoptime=time();

$time=$stoptime-$starttime;

$h=floor($time/3600);
$i=floor(($time-$h*3600)/60);
$s=$time-$h*3600-$i*60;

echo "<HR>";
echo $h."時間".$i."分".$s."秒";
echo "<HR>";
echo date("Y/m/d H:i:s",time());

#############################################################################################################################
?>
