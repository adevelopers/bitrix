<?

class CWrapSite{

	static public function getSectionNameById($SECTION_ID)
	{
		$ret = NULL;

		$res = CIBlockSection::GetByID($SECTION_ID);
		if($ar_res = $res->GetNext())
			$ret = $ar_res["NAME"];	

		return $ret;
	}


	static public function printablePrice($price)
	{
		$price = number_format($price, 0, ',', ' ');

		if (LANGUAGE_ID=="ru")
			return $price.'&nbsp;руб.';
		else
			return $price.'&nbsp;rub.';
	}


	/**
	* Функция получения ID инфоблока по его коду
	*/
	static public function getIBlockIDbyCode($code)
	{
		$strCode = trim($code); 

		if (!empty($strCode)) 
		{ 
			
			$res = CIBlock::GetList( 
				array(), 
				array( 
				//'TYPE'=>'xmlcatalog', 
					'ACTIVE'=>'Y', 
					"=CODE" => $strCode),
				true 
				); 

			while($ar_res = $res->Fetch()) 
			{ 
				$IBLOCK_ID=$ar_res["ID"]; 
			} 
			
		}
		return $IBLOCK_ID; 
	}
};


