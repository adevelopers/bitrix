<?php

/**
 * класс для работы с Бизнес процессами
 */
class CBPVisitor
{

	/**
	*
	* 1) шаг первый - создание Инфоблока
	* 2) шаг второй - создание шаблона Бизнес-Процесса
	* @param array $arResult - массив с данными
	* @param array $arParams - 
	*/
  static public function addBP($arResult,$arParams)
  {

	if(!CModule::IncludeModule("iblock")) { ShowError('Нет модуля iblock');}
  	if(!CModule::IncludeModule("bizproc")) { ShowError('Нет модуля bizproc');}

  	$ib = new CIBlock();
  	
  	$description = 'описание';
  	/*
  	 ШАГ 1
  	*/
    $arFields = array(
			"IBLOCK_TYPE_ID" => $arParams["IBLOCK_TYPE"],
			"LID" => "s1",//SITE_ID,
			"NAME" => $arResult["Data"]["Name"],
			"CODE" => "select_color",
			"ACTIVE" => 'Y',
			"SORT" => $arResult["Data"]["Sort"],
			"PICTURE" => intval($arResult["Data"]["Image"]) > 0 ? CFile::MakeFileArray($arResult["Data"]["Image"]) : false,
			"DESCRIPTION" => $description,
			"DESCRIPTION_TYPE" => 'text',
			"WORKFLOW" => 'N',
			"BIZPROC" => 'Y',
			"VERSION" => 1,
			"ELEMENT_ADD" => $arResult["Data"]["ElementAdd"],
		);
		
		foreach ($arResult["Data"]["UserGroups"] as $v)
			$arFields["GROUP_ID"][$v] = "R";

		if ($arParams["BLOCK_ID"] <= 0)
		{
			$opRes = $iblockId = $ib->Add($arFields);
		
		}
		else
		{
			$opRes = $ib->Update($arParams["BLOCK_ID"], $arFields);
			$iblockId = $arParams["BLOCK_ID"];
		
		}
	/*
  	 ШАГ 2
  	*/
  	 if ($opRes)
		{
			global $CACHE_MANAGER;
			$CACHE_MANAGER->Clean("component_bizproc_wizards_templates");

			if (intval($arResult["Data"]["Image"]) > 0)
				CFile::Delete($arResult["Data"]["Image"]);

			if ($arParams["BLOCK_ID"] <= 0 && strlen($arResult["Data"]["Template"]) > 0)
			{
				$arVariables = false;
				if (method_exists($bpTemplateObject, "GetVariables"))
				{
					$arVariables = $bpTemplateObject->GetVariables();
					$ks = array_keys($arVariables);
					foreach ($ks as $k)
						$arVariables[$k]["Default"] = $arResult["Data"]["TemplateVariables"][$k];
				}

				$arFieldsT = array(
					"DOCUMENT_TYPE" => array("bizproc", "CBPVirtualDocument", "type_".$iblockId),
					"AUTO_EXECUTE" => CBPDocumentEventType::Create,
					"NAME" => $arResult["Data"]["Name"],
					"DESCRIPTION" => $arResult["Data"]["Description"],
					"TEMPLATE" => $bpTemplateObject->GetTemplate(),
					"PARAMETERS" => $bpTemplateObject->GetParameters(),
					"VARIABLES" => $arVariables,
					"USER_ID" => $GLOBALS["USER"]->GetID(),
					"ACTIVE" => 'Y',
					"MODIFIER_USER" => new CBPWorkflowTemplateUser(CBPWorkflowTemplateUser::CurrentUser),
				);
				CBPWorkflowTemplateLoader::Add($arFieldsT);

				if (method_exists($bpTemplateObject, "GetDocumentFields"))
				{
					$runtime = CBPRuntime::GetRuntime();
					$runtime->StartRuntime();
					$arResult["DocumentService"] = $runtime->GetService("DocumentService");

					$arDocumentFields = $bpTemplateObject->GetDocumentFields();
					if ($arDocumentFields && is_array($arDocumentFields) && count($arDocumentFields) > 0)
					{
						foreach ($arDocumentFields as $f)
						{
							$arResult["DocumentService"]->AddDocumentField(
								array("bizproc", "CBPVirtualDocument", "type_".$iblockId),
								$f
							);
						}
					}
				}
			}

		/*
		* разворачиваем шаблон БП из файла
		*/
		$templateFileNameBP = $_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/adeveloper.bp/data/bp-sc.bpt';	
		$f = fopen($templateFileNameBP, "rb");
		$datum = fread($f, filesize($templateFileNameBP));
		fclose($f);


			try
			{
				$BLOCK_ID = $opRes;	
				$r = CBPWorkflowTemplateLoader::ImportTemplate(
					$ID,
					array("bizproc", "CBPVirtualDocument", "type_".$BLOCK_ID),
					1,
					"Шаблон БП",
					"Описание шаблона",
					$datum
				);
			}
			catch (Exception $e)
			{
				$errTmp = preg_replace("#[\r\n]+#", " ", $e->getMessage());
			} 

  		}
  		else
  		{
  			ShowError("Инфоблока нет");
  		}



  		return $opRes;
	}

};

