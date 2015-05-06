<?php


/**
 * класс обёртка для класса Пользователь CUser
 * 
 */
class CUserWrapper
{
	/**
	  * @param int $userID  - ID пользователя
	  * @return string - Фамилия Имя Отчество пользователя
	  */ 
  static public function getFIO($userID)
  {
    $rsUser = CUser::GetByID($userID);
    $arUser = $rsUser->Fetch();
    if (empty($arUser)) return false;
  
    $ret .= $arUser["LAST_NAME"];
    $ret .= ' '.$arUser["NAME"];
    $ret .= ' '.$arUser["SECOND_NAME"];
    return $ret;
  }

  /**
   * @param int $userID  - ID пользователя
   * @return string в формате БП - по умолчанию только Фамилия Имя - почта
   */
  static public function getPrintable($userID)
  {
   return CBPHelper::ConvertUserToPrintableForm($userID);
  }
};