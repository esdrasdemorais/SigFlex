<?php

class ContratosTipos {
	
	function ContratosTipos() {
		$objRegistry = Zend_Registry::getInstance();
		$this->objDb = $objRegistry->db;
	}
	
	function getContratosTipos() {
		$sql = "SELECT tbl_Contratos_Tipos.ID, tbl_Contratos_Tipos.Tipos_Contratos ";
		$sql.= "FROM tbl_Contratos_Tipos ";
		$sql.= "ORDER BY tbl_Contratos_Tipos.Tipos_Contratos ";
		
		$res = $this->objDb->query($sql);
		
		$contratosTipos = array();
		
		// Percorrendo os registros e armazenando na matriz $contratos
		foreach($res as $contratosTipo)
		{
			$contratosTipos[] = $contratosTipo;
		}
		
		// Retornando a matriz $contratos para popular o GRID
		return $contratosTipos;
	}
	
    public function updateContratosTipos($tiposContratos, $idTiposContratos)
    {
    	$sql = "UPDATE tbl_Contratos_Tipos SET ";
		$sql.= "Tipos_Contratos = '" . $tiposContratos . " ";
		$sql.= "WHERE ID = " . $idTiposContratos;
		
		$res = $this->objDb->query($sql);

       	if($res)
       	{
   			return true;
       	}
       	else
       	{
			throw new Exception($sql);
       	}
    }
}

?>