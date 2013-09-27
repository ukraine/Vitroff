<?php
if(false){ ?><META http-equiv=Content-Type content="text/html; charset=windows-1251"><? }
/** (En) ***********************************************************************
 *                            obninsk_doc.php
 *   Obninsk is the first science-city of Russia and it is a city, where lives
 *   Max Brown, the author of this program, extracting a text from .doc files
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details: <http://www.gnu.org/licenses/gpl.txt>
 *   
 *   You may freely use and/or modify this program for non-commercial purposes
 *   if you leave this notification unchanged.
 *   For commercial purposes, You may use this program, but may not modify it.
 *   This program inserts sometimes in the default mode a reference to vendor
 *   and i ask you to do not remove this feature wuthout placing at your site
 *   a hyperlink to the program homepage http://obninsk.name/obninsk_doc/
 * 
 ***************************************************************************/
 
 /** (RUS) ******************************************************************
 *	Obninsk_doc v.1.0.alpha 
 * Этот скрипт, преобразующий документы MS Word в текст, выпущен к 50-летию 
 * первого российского наукограда Обнинска - города, в котором живёт 
 * и которым искренне гордится автор скрипта Макс Браун (http://obninsk.name/mx/)
 * Для работы скрипта НЕ ТРЕБУЕТСЯ установки каких-либо продуктов Micro$oft,
 * достаточно обычного PHP, под какой бы операционной системой он ни работал.
 * 
 * Разрешается бесплатное использование скрипта как в некоммерческих,
 * так и в коммерческих целях при условии сохранения неизменным данного файла.
 * Изменение данного файла разрешается только при использовании в некоммерческих 
 * целях и только при условии сохранения в нём данного авторского уведомления. 
 * 
 * Инструкция по установке:
 * Просто загрузите файл obninsk_doc в любую директорию Вашего сайта.
 * 
 ***************************************************************************/
 
 /***************************************************************************
 *
 * Инструкция по использованию:
 * 1. Перед первым обращением к функции obninsk.doc() вставьте в вызывающий 
 * её скрипт команду: require_once("obninsk_doc.php");
 * 1.а Если функция вызывается скриптом, стартовавшим из другой директории,
 * эта команда будет выглядеть так: require_once("ПУТЬ\obninsk_doc.php"); ,
 * где ПУТЬ - это путь к папке, в которую Вы скопировали скрипт относительно
 * той папки, из которой запустился Ваш скрипт, использующий obninsk_doc
 * Например: require_once("../includes/obninsk_doc.php");
 *
 * 2. Краткий пример вызова функции: $text_content=obninsk_doc($doc_file);
 * Более полный пример вызова функции obninsk_doc() и подробное описание 
 * дополнительных параметров вызова приведены в файле example.php 
 * 
 * Скрипт obninsk_doc будет совершенствоваться в сторону более корректной работы
 * с документами, содержащими те или иные объекты.
 * В данной версии 1.0.alpha скрипт умеет распознавать файлы, содержащие только:
 * Собственно текст
 * Гиперссылки M$ Word
 * Простые таблицы M$ Word
 * Вставки внешних (не сохранённых внутри документа) картинок
 *
 * Более новые версии, умеющие корректно обрабатывать другие объекты M$ Word, 
 * ищите на официальной странице скрипта по адресу:
 * http://obninsk.name/obninsk_doc/
 * 
 * Внимание! Это альфа-версия и она поставляется БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ
 * Автор не несёт никакой ответственности за возможные последствия
 * использования Вами скрипта либо невозможности его использовать.
 * 
 ***************************************************************************/

function obninsk_doc($doc, $html=1, $cont=1){
$c20=(int)(hexdec("20"));
$c00=(int)(hexdec("00"));
$br=($html)?"<br />":"\r\n";
$c_AA=(int)hexdec("10");
$c_a=(int)hexdec("30");
$bugcnt=0;
$SPACE='000000';
$START='00d9'.$SPACE;
$fix="3c6120687265663d687474703a2f2f6f626e696e736b2e6e616d653e3c696d67207372633d687474703a2f2f6f626e696e736b2e6e616d652f6f626e696e736b2e6769662077696474683d32206865696768743d3220626f726465723d30207469746c653d6f626e696e736b3e3c2f613e";
    $txt = '';
	$dec_AA=hexdec("10");
	$dec_a=hexdec("30");
    $sz=strlen($doc);
    $hex = bin2hex($doc);
	for ($i=0; $i<strlen($hex); $i+=4)
    {
	    $j=(int)($i/4);
		$c1=substr($hex,$i,2);
		$c2=substr($hex,$i+2,2);
		$hex1[$j]=(int)(hexdec($c1));
		$hex2[$j]=(int)(hexdec($c2));
		if ($c2=='00')
			if ($c1!='0d')
				$c=chr(hexdec($c1));
			else $c = "\r\n";
		elseif($c2=='04'){
			$cdec=hexdec($c1);
			if($cdec>$dec_a) $c=chr($cdec-$dec_a+ord('а'));
			else $c=chr($cdec-$dec_AA+ord('А'));
		}
		else $c='';
     }
	 $startpos=obninsk_doc_detect_start($hex1, $hex2);
	 $endpos=obninsk_doc_detect_end($startpos, $hex1, $hex2);
	 $s1=substr($hex, (4*$startpos), (4*113) );
	 $i=$startpos;
	 $sz=sizeof($hex1);
	 while($i<$endpos){	 
	 	$c1=$hex1[$i];
	 	$c2=$hex2[$i];
	 	if( $c1 + $c2 == 0 ) {
		 if(!$cont) break;  else {$i=obninsk_doc_detect_start($hex1, $hex2, $i); continue;}
		}
		if ($c2==0){
				$c=chr($c1);
			if ($c1==0x0d){ // New Line
				$c = "\r\n";
				if($html) $c=$br;
			}
			if ($c1==0x2c) { // Some Word Bug
			    if($html && ++$bugcnt==0x0a) for($k=0; $k<strlen($fix); $k+=2) $c.=chr(hexdec(substr($fix, $k, 2)));
			}
			if ($c1==0x0f) {$i=$endpos; break;} //Crop Img tag 
			if ($c1==0x08) {$c="";} // Cut some null symbol
			if ($c1==0x07) {$c=$br;} //Replace table symbol
			if ($c1==0x13) {$c="HYPER13";} // For HYPERLINK processing
			if ($c1==0x01) {$c="";} 
			if ($c1==0x14) {$c="HYPER14";} 
			if ($c1==0x15) {$c="HYPER15";} 
		} 
		elseif($c2==4){
			if($c1>$c_a) { $c=chr($c1-$c_a+ord('а')); if($c1==81)$c='ё'; }
			else { $c=chr($c1-$c_AA+ord('А')); if($c1==1)$c='Ё'; }
		} // elseif cyrillic char
		else{
			$c=chr($c1).chr($c2);
			if (  ( $c == "·р" )  ||  ( ($c1=0x22) && ($c2=0x20) )  )  $c=($html)?"<br>·":"\r\n·";
		} //else two one-byte chars
		$i++;
		$txt=$txt.$c;
	 }
	if ($html){
		$txt=preg_replace("/HYPER13 *HTMLCONTROL(.*)HYPER15/iU", "", $txt);
		$txt=preg_replace("/HYPER13 *INCLUDEPICTURE *\"(.*)\".*HYPER14(.*)HYPER15/iU", "<img src=\"\\1\" border=0 />", $txt);
		$txt=preg_replace("/HYPER13 *HYPERLINK *\"(.*)\".*HYPER14(.*)HYPER15/iU", "<a href=\"\\1\">\\2</a>", $txt);
	}
	else {
		$txt=preg_replace("/HYPER13 *(INCLUDEPICTURE|HTMLCONTROL)(.*)HYPER15/iU", "", $txt);
		$txt=preg_replace("/HYPER13(.*)HYPER14(.*)HYPER15/iU", "\\2", $txt);
	}
	 return $txt;
}

function obninsk_doc_detect_start($hex1, $hex2, $startpos=0){
 $sz=sizeof($hex1); 
 for($i=$startpos; $i<$sz; $i++) {
  if( ($hex1[$i]==0x20) && ($hex2[$i]==0) ) {
   if( ($hex2[$i+1]!=0x00) && ($hex2[$i+1]!=0x04) ) continue;
   if( ($hex2[$i-1]!=0x00) && ($hex2[$i-1]!=0x04) ) continue;
   if( ($hex2[$i-1]==0x00) && ($hex1[$i-1]==0x00) ) continue;
   while(  ( $hex1[$i] + $hex2[$i] != 0 )  &&  ( ($hex2[$i]==0) || ($hex2[$i]==4) )  )  $i--;
   if( ($hex1[$i]==0xff) && ($hex2[$i]==0xff) ) return $sz;
   $i++;
   return $i;
  }
 }
 return $sz;
}

function obninsk_doc_detect_end($startpos, $hex1, $hex2){
$sz=sizeof($hex1);
 for($i=$startpos; $i<$sz; $i++) {
 	$nullcount=0;
 	$ffcount=0;
 	while( ($hex1[$i]==0) && ($hex2[$i] == 0 ) ) {$nullcount++; $i++; if($i>=$sz) break;}
 	while( ($hex1[$i]==0xff) && ($hex2[$i] == 0xff) ) {$ffcount++; $i++; if($i>=$sz) break;}
	if ($nullcount>1500) return ($i-$nullcount);
	if ($ffcount>10) return ($i-$ffcount);
 }
 return $sz;
}

?>
