<?

class CWrapSite{

	function getSectionNameById($SECTION_ID)
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
};


