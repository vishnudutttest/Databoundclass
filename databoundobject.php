<?php

abstract class DataBoundObject{
  
	protected $ID;
	protected $objDB;
	protected $strTableName;
	protected $arRelationMap;
	protected $blforDeletion;
	protected $blIsLoded;
	protected $arModifiedRelation;

	abstract protected function DefineTableName();
	abstract protected function DefineRelationMap();

	public function __construct(DB $db,$id=null){
		
		$this->strTableName= $this->DefineTableName();
		$this->arRelationMap= $this->DefineRelationMap();
		$this->objDB = $db;
		$this->blIsLoded = false;
		if(isset($id)){
			$this->ID = $id;
		}
		$this->arModifiedRelation = array();
	
	}

	public function Load(){
		if(isset($this->ID)){
			$strQuery = "SELECT ";
			foreach($this->arRelationMap as $key=>$value){
				$strQuery .= " `".$key."` ,";
			}
			$strQuery = substr($strQuery,0,strlen($strQuery)-1);
			$strQuery .= " FROM `".$this->strTableName."` WHERE `id` = ".$this->ID." ";

			$this->objDB->prepare($strQuery);
			$this->objDB->execute();
			$arRow = $this->objDB->fetch();

			foreach($arRow as $key => $value){
				$srtMember = $this->arRelationMap["$key"];
				if(property_exists($this,$srtMember)){
					if(is_numeric($value)){
						eval('$this->'.$srtMember.'='.$value.';');
					}else{
						eval('$this->'.$srtMember.'="'.$value.'";');
					}
				
				}
			}
			$this->blIsLoaded = true;
		}

	}

	public function Save(){
		if(isset($this->ID)){
			$strQuery = 'UPDATE `'.$this->strTableName.'` SET ';
			
			foreach($this->arRelationMap as $key=>$value){
				
				eval('$actualVal=&$this->'.$value.';');
				
				if(array_key_exists($key,$this->arRelationMap)){
					 if($key != 'id')
					 $strQuery .= " `$key` = '$actualVal' ,";
				}
				
			}
			$strQuery = substr($strQuery,0,strlen($strQuery)-1);
			$strQuery .= " WHERE id=".$this->ID;

			$this->objDB->prepare($strQuery);
			$this->objDB->execute();
			
		}else{
			$strValueList="";
			$strQuery = "INSERT INTO	`".$this->strTableName."` SET ";
			foreach($this->arRelationMap as $key=>$value){
				
				eval('$actualVal=&$this->'.$value.';');
				
				if(array_key_exists($key,$this->arRelationMap)){
					 if($key != 'id')
					 $strQuery .= " `$key` = '$actualVal' ,";
				}
				
			}
			$strQuery = substr($strQuery,0,strlen($strQuery)-1);
			$this->objDB->prepare($strQuery);
			$this->objDB->execute();
		}
	}

	public function MarkForDeletion(){
		$this->blforDeletion = TRUE;
	}
	
	public function __destruct(){
		 if(isset($this->ID)){
			if($this->blforDeletion == TRUE){
				$strQuery = "DELETE FROM `".$this->strTableName."` WHERE `id`=".$this->ID;
				$this->objDB->prepare($strQuery);
				$this->objDB->execute();
			}
		 }
	}

	public function __call($strFunction,$arArguments){
		$strMethodType = strtolower(substr($strFunction,0,3));
		$strMethodMember = substr($strFunction,3);
		
		switch($strMethodType){
			case "set" :
				return ($this->SetAccessor($strMethodMember,$arArguments));
			break;
			case "get":
				return ($this->GetAccessor($strMethodMember));
		}
		return (false);
	}

	private function SetAccessor($strMember,$strNewValue){
		$strNewValue = $strNewValue[0];
		if(property_exists($this,$strMember)){
			if(is_numeric($strNewValue)){
				eval('$this->'.$strMember.'='.$strNewValue.';');
			}else{
				
				eval('$this->'.$strMember.'="'.$strNewValue.'";');
			}
			$this->arModifiedRelation[$strMember] = "1";
		}else{
			return (false);
		}
	}

	private function GetAccessor($strMember){
		if($this->blIsLoaded != true){
			$this->Load();
		}
		
		if(property_exists($this,$strMember)){
			eval('$strValue = $this->'.$strMember.';');	
			return $strValue;
		}else{
			return (false);
		}
	}
	
}
