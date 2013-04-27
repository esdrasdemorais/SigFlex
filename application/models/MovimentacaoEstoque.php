<?php

class MovimentacaoEstoque
{
    private $objDb;
    
    public function MovimentacaoEstoque()
    {
        $objRegistry = Zend_Registry::getInstance();
        $this->objDb = $objRegistry->db;
    }
    
    public function getMovimentacoes($filtro = null)
    {
        $sql = "SELECT	TABMOV.CodMov AS [Cód Mov], ESTPRO.cd_mostra AS Produto, ";
        $sql.= "		PROPRO.Descr_Tecnica AS [Descrição], MOVPRO.qtde AS [Qtd Mov], ";
        $sql.= "		TABMOV.CC AS [Centro Custo], TABMOV.setor AS Setor, ";
        $sql.= "		PROUNIMET.Descricao AS Unidade, PROCOR.Cores AS Cor, ";
        $sql.= "		TABMOV.tipo AS Tipo, PROCLA.Classes AS [Família] ";
        $sql.= "FROM (((((((tab_mov AS TABMOV ";
        $sql.= "INNER JOIN movproduto AS MOVPRO";
        $sql.= "	ON TABMOV.CodMov = MOVPRO.CodMov) ";
        $sql.= "INNER JOIN tbl_Est_Prod AS ESTPRO";
        $sql.= "	ON MOVPRO.ID = ESTPRO.cd_prod) ";
        $sql.= "INNER JOIN tbl_Prod_Detalhes AS PRODET ";
        $sql.= "	ON ESTPRO.cd_prod = PRODET.ID) ";
        $sql.= "INNER JOIN tbl_Prod_Unid_Metrica AS PROUNIMET ";
        $sql.= "	ON PRODET.Ident_Unid_Metrica = PROUNIMET.ID) ";
        $sql.= "INNER JOIN tbl_Prod_Cores AS PROCOR ";
        $sql.= " 	ON PRODET.Ident_Cor = PROCOR.ID)";
        $sql.= "INNER JOIN tbl_Prod_Produto AS PROPRO";
        $sql.= "	ON PRODET.Ident_Prod_Produtos = PROPRO.ID) ";
        $sql.= "INNER JOIN tbl_Prod_Classes AS PROCLA";
        $sql.= "	ON PROPRO.Ident_Classes_Prod = PROCLA.ID) ";
        // 16 = Uniformes
        $sql.= "WHERE PROCLA.Cod_Classes_Anal = 16 ";
        // 26 = Equipamentos
        $sql.= "OR PROCLA.Cod_Classes_Anal = 26 ";
        $sql.= $filtro;
        $sql.= " ORDER BY TABMOV.data DESC";
        return $sql;exit;
        $sql.= ($filtro) ? $filtro->getFiltro() : "";
        $res = $this->objDb->query($sql);
        
        $movimentacoes = array();
        foreach($res as $movimentacao)
        {
            $movimentacoes[] = $movimentacao;
        }
        
        return $movimentacoes;
    }
}