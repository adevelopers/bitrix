<?php

/**
 * 
 * класс обёртка для класса CIBlock
 */
class CIBlockWrapper
{


	/**
	 * @param string $code код инфблока
	 * @return int ID инфоблока
	 */
	static public function GetIdByCode($code)
	{
		$res = CIBlock::GetList(
	    Array(), 
	    Array(
	        'TYPE'=>'bizproc_iblockx', 
	        'SITE_ID'=>SITE_ID, 
	        'ACTIVE'=>'Y', 
	        "CODE"=>$code
	    ), true
		);

		if (empty($res))
		{
			ShowError("Инфоблок с кодом=".$code." не найден");	
			return 0;
		} 
		$data = $res->GetNext();

		return $data["ID"];
	}

};