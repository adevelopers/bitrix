<?

class CPaginationWrapper{

	 /**
	 * Функция возвращает внятный  структурированный массив по страницам, а не то говно которое даёт битрикс изначально
	 *
	 */
	static public function getNav($arResult)
	{
		$cntPages = floor($arResult["NavRecordCount"]/$arResult["NavPageSize"]);
		$currentPageNum = $arResult["NavPageNomer"];
		$strSelectPath = $arResult['sUrlPathParams'].($arResult["bSavePage"] ? '&PAGEN_'.$arResult["NavNum"].'='.(true !== $arResult["bDescPageNumbering"] ? 1 : '').'&' : '').'SHOWALL_'.$arResult["NavNum"].'=0&SIZEN_'.$arResult["NavNum"].'='; 

		for ($i=1;$i<=($cntPages+1);$i++)
		{
		$array["PAGES"][$i]['TITLE'] = $i; 
		$array["PAGES"][$i]['LINK']  = $strSelectPath.'&PAGEN_1='.$i; 
		$array["PAGES"][$i]['IS_CURRENT'] = ($i==$currentPageNum ? true:false);
		}

		return $array;

	}

};
