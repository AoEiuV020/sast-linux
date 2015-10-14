<?php
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
		switch(substr($content,0,4))
		{
		case "img:":
			$img = $doc->createElement('img');
			$content=str_replace(array("\n","\r"),"",$content);
			$img->setAttribute("src",substr($content,4));
			$p->appendChild($img);
			break;
		case "lin:"://link...
			$a = $doc->createElement('a');
			$content=str_replace(array("\n","\r"),"",$content);
			$a->setAttribute("href",substr($content,4));
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
?>