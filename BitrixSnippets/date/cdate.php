<?php



/**
*  Класс для работы с датами в битриксе
*/
class CDate 
{
	
	function __construct(argument)
	{
		# code...
	}


  /**
  * @param string $sDateTime - Дата в формате КП
  * @return дата и время в усечёном варианте;
  * @todo В дальнейшем надо будет переписать, слабое место, если на кор-портале настройки формата дат изменеы то возможны сложности с конвертацией
  */
  static public function  dateToFormat($sDateTime)
  {

    return CIBlockFormatProperties::DateFormat('d.m.Y h:i', MakeTimeStamp($sDateTime,"YYYY-MM-DD HH:MI:SS"));

  }
}
  
