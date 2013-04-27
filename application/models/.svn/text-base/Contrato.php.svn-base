<?php

class Contrato
{
	function Contrato()
	{
		$objRegistry = Zend_Registry::getInstance();
		$this->objDb = $objRegistry->db;
	}
	
	function getContratos()
	{
		$sql = "SELECT tbl_Contratos.ID, tbl_Contratos.Numero, tbl_Contratos.Ano, "; 
		$sql.= "tbl_Contratos.Ident_Tipo_Contrato, tbl_Contratos.Ident_Tipo_Mod, ";
		$sql.= "tbl_Contratos.Ident_Cotacoes, tbl_Contratos.Data_Emiss_Contrato, ";
		$sql.= "tbl_Contratos.Usuario_Contrato, tbl_Contratos.Ident_Setor, tbl_Contratos.Obs, ";
		$sql.= "[numero] & 	'/' & [ano] AS nreq, tbl_Contratos.num_Mod, tbl_Contratos.just, ";
		$sql.= "tbl_Contratos.dataconfirm ";
		$sql.= "FROM tbl_Contratos";
		
		$res = $this->objDb->query($sql);
		
		$contratos = array();
		
		// Percorrendo os registros e armazenando na matriz $contratos
		foreach($res as $contrato)
		{
			$contratos[] = $contrato;
		}
		
		// Retornando a matriz $contratos para popular o GRID
		return $contratos;
	}
	
	function salvarContrato($id, $numero, $ano, $identTipoContrato, $identTipoMod, $identCotacoes, 
		$dataEmissContrato, $usuarioContrato, $identSetor, $obs, $numMod, $just, $dataConfirm)
	{
		$sql = "INSERT INTO tbl_Contratos (";
		$sql.= "ID, Numero, Ano, Ident_Tipo_Contrato, Ident_Tipo_Mod, Ident_Cotacoes, Data_Emiss_Contrato, ";
		$sql.= "Usuario_Contrato, Ident_Setor, Obs, num_Mod, just, dataconfirm";
		$sql.= ")VALUES(";
		$sql.= $id . ", " . $numero . ", '" . $ano . "', " . $identTipoContrato . ", " . $identTipoMod . ", ";
		$sql.= $identCotacoes . ", '" . $dataEmissContrato . "', '" . $usuarioContrato . "', " . $identSetor . ", ";
		$sql.= "'" . $obs . "', '" . $numMod . "', '" . $just . "', '" . $dataConfirm . "'";
		
		$res = $this->objDb->query($sql);
		
		return $res;
	}
}