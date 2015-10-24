<?php
if($_SERVER['SERVER_NAME']==="linux.aoeiuv020.top")
{
	$url='http://'."sastlinux.cn".':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$html1=<<<EOF

<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <meta http-equiv=Refresh content="10; url=
EOF;
	$html2=<<<EOF
">
<style type=text/css>
* {margin:0; padding:0; font-family: "Microsoft YaHei" ! important;}body{background:#fff;margin:200px auto;font:14px/150% Verdana,Georgia,Sans-Serif;color:#000;text-align:center}A:link{color:#2c4c78;TEXT-DECORATION:none}A:visited{color:#2c4c78;TEXT-DECORATION:none}A:hover{color:#2c4c78;TEXT-DECORATION:none}A:active{color:#2c4c78;TEXT-DECORATION:none}H1{PADDING-RIGHT:4px;PADDING-LEFT:4px;FONT-SIZE:18px;line-height:34px;color:#333333;BACKGROUND:#fff;PADDING-BOTTOM:4px;MARGIN:0;PADDING-TOP:4px;BORDER-BOTTOM:#ccc 0px solid}div{ACKGROUND:#fff;MARGIN:0 auto;WIDTH:90%}P{color:#333333;PADDING-RIGHT:15px;PADDING-LEFT:15px;PADDING-BOTTOM:15px;MARGIN:0;PADDING-TOP:15px}
</style>
</head>
<body><div><h1>本站已经更换新域名，10秒过后系统将会自动跳转到新域名，<br>请得到新域名过后下次改用新域名上本站，此旧域名将要作废。</h1><p>如果无法自动跳转，请点击 <a href="
EOF;
	$html3=<<<EOF
"><u>这里</u></a>。</p></div></body>
</html>
EOF;

echo $html1.$url.$html2.$url.$html3;
}
else
{
$doc = new DOMDocument('1.0','utf-8');
$doc->formatOutput=true;
//$doc->encodeing='utf-8';
$html = $doc->createElement('html');
$doc->appendChild($html);
$head = $doc->createElement('head');
$html->appendChild($head);
$meta = $doc->createElement('meta');
$meta->setAttribute("charset","utf-8");
$head->appendChild($meta);
$title = $doc->createElement('title');
$head->appendChild($title);
$text = $doc->createTextNode('Linux');
$title->appendChild($text);
$body = $doc->createElement('body');
$html->appendChild($body);
$h1 = $doc->createElement('h1',"南邮-校科协-计算机部-Linux组");
$body->appendChild($h1);

$p = $doc->createElement('p');
$info_txt="info.txt";
if(file_exists($info_txt))
{
	$fpinfo = fopen($info_txt,"r");
	while(!feof($fpinfo))
	{
		$content=fgets($fpinfo);
		$content=str_replace(array("\n","\r"),"",$content);
		switch(substr($content,0,4))
		{
		case "img:":
			$img = $doc->createElement('img');
			$img->setAttribute("src",substr($content,4));
			$p->appendChild($img);
			break;
		case "lin:"://link...
			$a = $doc->createElement('a');
			$a->setAttribute("href",substr($content,4));
			$text = $doc->createTextNode(fgets($fpinfo));
			$a ->appendChild($text);
			$p->appendChild($a);
			break;
		case "eli:"://external link...
			$a = $doc->createElement('a');
			$a->setAttribute("href",substr($content,4));
			$a->setAttribute('target',"_blank");
			$text = $doc->createTextNode(fgets($fpinfo));
			$a ->appendChild($text);
			$p->appendChild($a);
			break;
		default:
			$span = $doc->createElement('span');
			$text = $doc->createTextNode($content);
			$span ->appendChild($text);
			//$span ->nodeValue=htmlspecialchars($content);
			$p->appendChild($span);
		}
		$br = $doc->createElement('br');
		$p->appendChild($br);
	}
	fclose($fpinfo);
}
$body->appendChild($p);

$p = $doc->createElement('p');
/*
 * 返回根目录的a标签，
 */
$a = $doc->createElement('a');
$a -> setAttribute('href',"/");
$text = $doc->createTextNode("返回根目录");
$a -> appendChild($text);
$p -> appendChild($a);
$br = $doc->createElement('br');
$p -> appendChild($br);
/*
 * 扫描当前目录，用a标签链接出每个文件夹，
 * 读出每个目录里的info.txt里的第一行作为a标签的value，
 */
$dir = opendir('.');
$exclude = array(".",".git","README.md","img","res",basename(__FILE__),"info.txt","link.txt","about.txt","logreport");
while(($file=readdir($dir))!==false)
{
	$flag=false;
	foreach($exclude as $excludefile)
	{
		if($excludefile===$file)
		{
			$flag=true;
		}
	}
	if($flag)
	{
		continue;
	}
	$files[]=$file;
}
asort($files);
foreach($files as $file)
{
	$a = $doc->createElement('a');
	$a -> setAttribute('href',$file);
	$info = $file;
	if($file==="..")
	{
		$info="返回上级";
	}
	if(is_dir($file))
	{
		$info_txt=$file."/info.txt";
		if(file_exists($info_txt))
		{
			$fpinfo = fopen($info_txt,"r");
			if(!feof($fpinfo))
			{
				$info=$info." : ".fgets($fpinfo);
			}
			fclose($fpinfo);
		}
	}
	$text = $doc->createTextNode($info);
	$a ->appendChild($text);
	$p -> appendChild($a);
	$br = $doc->createElement('br');
	$p->appendChild($br);
}
$body->appendChild($p);

$p = $doc->createElement('p');
$link_txt="link.txt";
if(file_exists($link_txt))
{
	$span = $doc->createElement('span');
	$span->nodeValue="下面是些外部链接，";
	$p->appendChild($span);
	$br = $doc->createElement('br');
	$p->appendChild($br);
	$fplink = fopen($link_txt,"r");
	while(!feof($fplink))
	{
		$a = $doc->createElement('a');
		list($href,$link)=fscanf($fplink,"%s%s");
		$a -> setAttribute('href',$href);
		$a -> setAttribute('target',"_blank");
		$text = $doc->createTextNode($link);
		$a ->appendChild($text);
		$p->appendChild($a);
		$br = $doc->createElement('br');
		$p->appendChild($br);
	}
	fclose($fplink);
}
$body->appendChild($p);

echo "<!doctype html>\n";
echo $doc->saveHTML();
}
?>

