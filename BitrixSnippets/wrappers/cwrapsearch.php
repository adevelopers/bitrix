<?

class CWrapSearch{

	static public function getTags()
	{
		$tags = CSearchTags::GetList();
		while($tag = $tags->getNext())
		{
			$key++;
			$arTags[$key]=array("TAG_NAME"=>$tag["NAME"],"TAG_PATH"=>"#","CNT"=>$tag["CNT"]);

		}

		return $arTags;
	}
}