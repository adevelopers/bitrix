<?php

$lvl=1;
function revstr($cnt,$s)
{
	static $lvl;
	if ($cnt <= 0) return ;
	$newstr = substr($s,0,strlen($s)-1);
	$cnt = strlen($newstr);
	
	return ' '.($lvl++).':['.$s[$cnt].revstr(strlen($newstr),$newstr).']';

}

$s = 'abcdef';
dr($s);
dr(revstr(strlen($s),$s));

?>