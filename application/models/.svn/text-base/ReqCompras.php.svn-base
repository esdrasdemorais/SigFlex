<?php

class ReqCompras //extends Zend_Db_Table
{
	//protected $_name 	= "tbl_Req_Compras";
	//protected $_primary = array("Num_Req", "Ano");
	
	public function ReqCompras()
	{
		/** Obtendo o objeto db registrado no bootstrap index.php **/
		$objRegistry = Zend_Registry::getInstance();
		$this->objDb = $objRegistry->db;
	}
	
	public function cslReqCompras($idReqCompra)
	{
		$sql = "SELECT tbl_Req_Compras.ID, tbl_Req_Compras.Num_req, tbl_Req_Compras.Ano, tbl_Req_Compras.Process, tbl_Req_Compras.Data_Emiss, ";
		$sql.= "tbl_Req_Compras.Ident_setor, tbl_Req_Compras.Justificativa, tbl_Req_Compras.Ident_local_entrega, tbl_Req_Compras.Requisitante, ";
		$sql.= "tbl_Req_Compras.Destinacao, tbl_Req_Compras.Prazo_solic, tbl_Req_Compras.Valor_Estimado, tbl_Req_Compr_Detalhes.ID, ";
		$sql.= "tbl_Req_Compr_Detalhes.Item, tbl_Req_Compr_Detalhes.cod_Produto, tbl_Req_Compr_Detalhes.Ident_req_Compras, ";
		$sql.= "tbl_Req_Compr_Detalhes.Desc_mat, tbl_Req_Compr_Detalhes.unidade, tbl_Req_Compr_Detalhes.quantidade, tbl_Local_Entrega.DESCRICAO, ";
		$sql.= "csl_Setor.Sigla AS Setor1, Setor.Sigla AS Dir, Setor.Responsavel AS DirR, Setor_1.Sigla AS Ger, Setor_1.Responsavel AS GerR, ";
		$sql.= "tbl_Req_Compras.Ident_C_Custo, tbl_Est_Prod.qtd_sld, tbl_Est_Prod.vlr_ult_compra, csl_Ultima_aquisicao.DT_Cadastro, ";
		$sql.= "IIF(IsNull(Round([qtde]/3)), 0, Round([qtde]/3)) AS c_Medio1, tbl_Req_Compras.Usuario, tbl_Req_Compras.Data_Digit ";
		$sql.= "FROM (";
		$sql.= "tbl_Local_Entrega ";
		$sql.= "RIGHT JOIN (((tbl_Req_Compras ";
		$sql.= "	LEFT JOIN csl_Setor ON tbl_Req_Compras.Ident_setor = csl_Setor.Cod_Setor) ";
		$sql.= "	LEFT JOIN Setor ON csl_Setor.CAD = Setor.Cod_Analitico_Setor) ";
		$sql.= "	LEFT JOIN Setor AS Setor_1 ON csl_Setor.CAG = Setor_1.Cod_Analitico_Setor) ";
		$sql.= "ON tbl_Local_Entrega.ID = tbl_Req_Compras.Ident_local_entrega";
		$sql.= ") ";
		$sql.= "LEFT JOIN (((tbl_Req_Compr_Detalhes ";
		$sql.= "	LEFT JOIN tbl_Est_Prod ON tbl_Req_Compr_Detalhes.cod_Produto = tbl_Est_Prod.cd_mostra) ";
		$sql.= "	LEFT JOIN csl_Ultima_aquisicao ON tbl_Est_Prod.cd_prod = csl_Ultima_aquisicao.ID) ";
		$sql.= "	LEFT JOIN csl_consumo_medio_soma ON tbl_Est_Prod.cd_prod = csl_consumo_medio_soma.ID) ";
		$sql.= "ON tbl_Req_Compras.ID = tbl_Req_Compr_Detalhes.Ident_req_Compras ";
		$sql.= "WHERE (tbl_Req_Compras.ID = " . $idReqCompra . ")";
		$res = $this->objDb->query($sql);
		
		$requisicoes = array();
		
		foreach($res as $requisicao)
		{
			$requisicoes[] = $requisicao;
		}
		
		return $requisicoes;
	}
}