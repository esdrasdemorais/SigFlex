<?php
class Controle
{
    private $objDb;
    public function Controle ()
    {
        $objRegistry = Zend_Registry::getInstance();
        $this->objDb = $objRegistry->db;
    }
    public function getRequisicoes ()
    {
        //talvez deva ser mudada pois pode funcionar somente no SQL-Server e MS Access
        $sql = "SELECT tbl_Req_Compras.ID, str((tbl_Req_Compras.Data_Aprov)), ";
        $sql .= "IIf([corrigir]=-1,1999,[ano]) AS Ano1, ";
        $sql .= "IIf([corrigir]=-1,Right('00000' & [Num_req],5) & '/' & [ano] & '*', "; 
        $sql.= "Right('00000' & [Num_req],5) & '/' & [ano]) AS REQUIS, ";
        $sql .= "FORMAT(tbl_Req_Compras.Data_Emiss,'dd/mm/yyyy') AS [DT EMISS], ";
        $sql .= "FORMAT(tbl_Req_Compras.Data_Aprov, 'dd/mm/yyyy') AS [DT SUPRIM], ";
        $sql .= "tbl_Req_Compras.Requisitante ";
        $sql .= "FROM tbl_Req_Compras ";
        $sql .= "WHERE ((str((tbl_Req_Compras.Data_Aprov)) is not null) And ";
        $sql .= "(((tbl_Req_Compras.Data_Distr_Compr))is null) And ";
        $sql .= "(((tbl_Req_Compras.Ident_Cotacoes)) is null) And ";
        $sql .= "(((tbl_Req_Compras.Data_Cancel)) is null)) ";
        $sql .= "ORDER BY IIf([corrigir]=-1,1999,[ano]), ";
        $sql.= "IIf([corrigir]=-1,Right('00000' & [Num_req],5) & '/' & [ano] & '*',";
        $sql.= "Right('00000' & [Num_req],5) & '/' & [ano]);";
        $res = $this->objDb->query($sql);
        $controles = array();
        foreach ($res as $controle) {
            $controles[] = $controle;
        }
        //print_r($controles);exit;
        return $controles;
    }
    public function getRequisicoesPendentesCorrecoes ()
    {
        $sql = "SELECT tbl_Req_Compras.ID, tbl_Req_Compras.Ano, ";
        $sql .= "Right('00000' & [Num_req],5) & '/' & [ano] AS REQUIS, ";
        $sql .= "FORMAT(tbl_Req_Compras.Data_Emiss, 'dd/mm/yyyy') AS [DT EMISS], ";
        $sql .= "tbl_Compradores.NOME_COMPRAD ";
        $sql .= "FROM tbl_Compradores ";
        $sql .= "RIGHT JOIN tbl_Req_Compras ON tbl_Compradores.ID = tbl_Req_Compras.Ident_Comprador ";
        $sql .= "WHERE tbl_Req_Compras.corrigir = True ";
        $sql .= "ORDER BY tbl_Req_Compras.Ano, Right('00000' & [Num_req],5) & '/' & [ano];";
        $res = $this->objDb->query($sql);
        $requisicoes = array();
        foreach ($res as $requisicao) {
            $requisicoes[] = $requisicao;
        }
        return $requisicoes;
    }
    public function getApCotacoes ()
    {
        $sql = "SELECT tbl_Cotacoes.ID, tbl_Cotacoes.ID AS idd, ";
        $sql .= "	Format([tbl_cotacoes]![ID],'000000') AS COTAÇÕES, ";
        $sql .= "	FORMAT(tbl_Cotacoes.Data_Emiss_Cotac, 'dd/mm/yyyy') AS [DT EMISS], ";
        $sql .= "	FORMAT(tbl_Cotacoes.Data_Grade, 'dd/mm/yyyy') AS [DT GRADE], ";
        $sql .= "	Sum(tbl_Cotacoes_Detalhes.Val_Total) AS TOTAL, ";
        $sql .= "	tbl_Cotacoes_Detalhes.Compra ";
        $sql .= "FROM ((tbl_Compradores ";
        $sql .= "RIGHT JOIN (tbl_Cotacoes ";
        $sql .= "	LEFT JOIN tbl_Contratos ON tbl_Cotacoes.ID = tbl_Contratos.Ident_Cotacoes) ";
        $sql .= "ON tbl_Compradores.usuario = tbl_Cotacoes.Usuario_Cotac) ";
        $sql .= "LEFT JOIN tbl_Fornec_Selec ON tbl_Cotacoes.ID = tbl_Fornec_Selec.Cod_Cotac) ";
        $sql .= "LEFT JOIN tbl_Cotacoes_Detalhes ON tbl_Fornec_Selec.ID = tbl_Cotacoes_Detalhes.Ident_Emp_selec ";
        $sql .= "GROUP BY tbl_Cotacoes.ID, tbl_Cotacoes.ID, Format([tbl_cotacoes]![ID],'000000'), ";
        $sql .= "	tbl_Cotacoes.Data_Emiss_Cotac, ";
        $sql .= "	tbl_Cotacoes.Data_Grade, tbl_Cotacoes_Detalhes.Compra, tbl_Cotacoes.Usuario_Cotac, ";
        $sql .= "	tbl_Cotacoes.Data_Aprov_Comp, ";
        $sql .= "	tbl_Cotacoes.dt_cancel_Cot, tbl_Contratos.Ident_Cotacoes ";
        $sql .= "HAVING (((tbl_Cotacoes.Data_Emiss_Cotac) > #1/1/2006#) ";
        $sql .= "And ((tbl_Cotacoes.Data_Grade) Is Not Null) And ((tbl_Cotacoes_Detalhes.Compra) = True) ";
        $sql .= "And ((tbl_Cotacoes.Data_Aprov_Comp) Is Null) And ((tbl_Cotacoes.dt_cancel_Cot) Is Null) ";
        $sql .= "And ((tbl_Contratos.Ident_Cotacoes) Is Null)) ";
        $sql .= "ORDER BY Format([tbl_cotacoes]![ID],'000000');";
        $res = $this->objDb->query($sql);
        $apCotacoes = array();
        foreach ($res as $apCotacao) {
            $apCotacao['TOTAL'] = "R$ " . number_format($apCotacao['TOTAL'], 2, ",", ".");
            $apCotacoes[] = $apCotacao;
        }
        return $apCotacoes;
    }
    public function getApCotacoesPendentesAprovacao ($siglaAprov)
    {
        $sql = "SELECT tbl_Cotacoes.ID, tbl_Cotacoes.ID AS idd, ";
        $sql .= "	FORMAT([tbl_Cotacoes]![ID],'000000') AS COTAÇÕES, ";
        $sql .= "	FORMAT(tbl_Cotacoes.Data_Emiss_Cotac, 'dd/mm/yyyy') AS [DT EMISS], ";
        $sql .= "	FORMAT(tbl_Cotacoes.Data_Aprov_Comp, 'dd/mm/yyyy') AS [DT APROV], ";
        $sql .= "	tbl_Cotacoes.sigla_aprov ";
        $sql .= "FROM tbl_Compradores ";
        $sql .= "RIGHT JOIN (tbl_Cotacoes ";
        $sql .= "	LEFT JOIN tbl_Contratos ON tbl_Cotacoes.ID = tbl_Contratos.Ident_Cotacoes) ";
        $sql .= "ON tbl_Compradores.usuario = tbl_Cotacoes.Usuario_Cotac ";
        $sql .= "WHERE (((tbl_Cotacoes.Data_Aprov_Comp) Is Not Null) ";
        $sql .= "AND ((tbl_Cotacoes.sigla_aprov)='" . $siglaAprov . "') ";
        $sql .= "AND ((tbl_Cotacoes.Data_Aprov_Dir) Is Null) AND ((tbl_Cotacoes.dt_cancel_Cot) Is Null) ";
        $sql .= "AND ((tbl_Contratos.Ident_Cotacoes) Is Null));";
        $res = $this->objDb->query($sql);
        $requisicoes = array();
        foreach ($res as $requisicao) {
            $requisicoes[] = $requisicao;
        }
        return $requisicoes;
    }
    public function getCotacoesPendentesAprovacaoDir ($siglaAprov)
    {
        $sql = "SELECT tbl_Cotacoes.ID, ";//tbl_Cotacoes.ID AS Idd,
        $sql .= "Format(tbl_cotacoes!ID,'000000') AS COTAÇÕES, ";
        $sql .= "FORMAT(tbl_Cotacoes.Data_Emiss_Cotac, 'dd/mm/yyyy') AS [DT EMISS], ";
        $sql .= "FORMAT(tbl_Cotacoes.Data_Grade, 'dd/mm/yyyy') AS [DT GRADE], ";
        $sql .= "Sum(tbl_Cotacoes_Detalhes.Val_Total) AS VALOR, "; //format(tbl_Cotacoes_Detalhes.Val_Total,"###,###,###0.00") ou (tbl_Cotacoes_Detalhes.Val_Total,"###,###,####.##")
        $sql .= "tbl_Cotacoes.Usuario_Cotac AS COMPR, ";
        $sql .= "tbl_Cotacoes.sigla_aprov ";
        $sql .= "FROM ((tbl_Compradores ";
        $sql .= "RIGHT JOIN (tbl_Cotacoes ";
        $sql .= " LEFT JOIN tbl_Contratos ON tbl_Cotacoes.ID = tbl_Contratos.Ident_Cotacoes) ";
        $sql .= "ON tbl_Compradores.usuario = tbl_Cotacoes.Usuario_Cotac) ";
        $sql .= "LEFT JOIN tbl_Fornec_Selec ON tbl_Cotacoes.ID = tbl_Fornec_Selec.Cod_Cotac) ";
        $sql .= "LEFT JOIN tbl_Cotacoes_Detalhes ON tbl_Fornec_Selec.ID = tbl_Cotacoes_Detalhes.Ident_Emp_selec ";
        $sql .= "GROUP BY tbl_Cotacoes.ID, tbl_Cotacoes.ID, ";
        $sql .= "Format(tbl_cotacoes!ID,'000000'), ";
        $sql .= "tbl_Cotacoes.Data_Emiss_Cotac, ";
        $sql .= "tbl_Cotacoes.Data_Grade, ";
        $sql .= "tbl_Cotacoes.Usuario_Cotac, ";
        $sql .= "tbl_Cotacoes.sigla_aprov, ";
        $sql .= "tbl_Cotacoes.Data_Aprov_Comp, tbl_Cotacoes.Data_Aprov_Dir, tbl_Cotacoes.Data_Bloque_Dir, ";
        $sql .= "tbl_Cotacoes.dt_cancel_Cot, tbl_Contratos.Ident_Cotacoes, tbl_Cotacoes_Detalhes.Compra ";
        $sql .= "HAVING (((tbl_Cotacoes.Data_Grade) Is Not Null) ";
        $sql .= "AND ((tbl_Cotacoes.sigla_aprov)='" . $siglaAprov . "') ";
        $sql .= "AND ((tbl_Cotacoes.Data_Aprov_Comp) Is Not Null) ";
        $sql .= "AND ((tbl_Cotacoes.Data_Aprov_Dir) Is Null) ";
        $sql .= "AND ((tbl_Cotacoes.Data_Bloque_Dir) Is Null) ";
        $sql .= "AND ((tbl_Cotacoes.dt_cancel_Cot) Is Null) ";
        $sql .= "AND ((tbl_Contratos.Ident_Cotacoes) Is Null) ";
        $sql .= "AND ((tbl_Cotacoes_Detalhes.Compra)=True)) ";
        $sql .= "ORDER BY Format(tbl_cotacoes!ID,'000000');";
        $res = $this->objDb->query($sql);
        $requisicoes = array();
        foreach ($res as $requisicao) {
            $requisicao['VALOR'] = "R$ " . number_format($requisicao['VALOR'], 2, ",", ".");
            $requisicoes[] = $requisicao;
        }
        return $requisicoes;
    }
    public function getCotacoesAprovadasDir ($siglaAprov)
    {
        $sql = "SELECT tbl_Cotacoes.ID, "; //tbl_Cotacoes.ID AS idd, 
        $sql .= "	FORMAT([tbl_Cotacoes]![ID],'000000') AS COTAÇÕES, ";
        $sql .= "	FORMAT(tbl_Cotacoes.Data_Emiss_Cotac, 'dd/mm/yyyy') AS [DT EMISS], ";
        $sql .= "	FORMAT(tbl_Cotacoes.Data_Grade, 'dd/mm/yyyy') AS [DT GRADE], ";
        $sql .= "	tbl_Cotacoes.Usuario_Cotac AS COMPR, ";
        $sql .= "	tbl_Cotacoes.sigla_aprov ";
        $sql .= "FROM tbl_Compradores ";
        $sql .= "RIGHT JOIN (tbl_Cotacoes ";
        $sql .= "	LEFT JOIN tbl_Contratos ON tbl_Cotacoes.ID = tbl_Contratos.Ident_Cotacoes) ";
        $sql .= "ON tbl_Compradores.usuario = tbl_Cotacoes.Usuario_Cotac ";
        $sql .= "WHERE tbl_Cotacoes.Data_Grade Is Not Null ";
        $sql .= "AND tbl_Cotacoes.sigla_aprov='" . $siglaAprov . "' ";
        $sql .= "AND tbl_Cotacoes.Data_Aprov_Comp Is Not Null ";
        $sql .= "AND tbl_Cotacoes.Data_Aprov_Dir Is Not Null ";
        $sql .= "AND tbl_Cotacoes.Data_Bloque_Dir Is Null ";
        $sql .= "AND tbl_Cotacoes.dt_cancel_Cot Is Null ";
        $sql .= "AND tbl_Contratos.Ident_Cotacoes Is Null ";
        $sql .= "ORDER BY Format(tbl_cotacoes!ID,'000000');";
        $res = $this->objDb->query($sql);
        $requisicoes = array();
        foreach ($res as $requisicao) {
            $requisicoes[] = $requisicao;
        }
        return $requisicoes;
    }
    public function getCotacoes ($idComprador, $dataEmissaoCotacao = null, $comDataGrade = null)
    {
        $andSql = "";
        if (strlen($dataEmissaoCotacao)) {
            $andSql = " AND tbl_Cotacoes.Data_Emiss_Cotac > cdate('" . $dataEmissaoCotacao . "') ";
        }
        if ($comDataGrade) {
            $andSql .= " AND tbl_Cotacoes.Data_Grade Is Not Null ";
        } else {
            $andSql .= " AND tbl_Cotacoes.Data_Grade Is Null ";
        }
        $sql = "SELECT tbl_Cotacoes.ID, tbl_Compradores.ID AS [ID_Comprador], tbl_Cotacoes.ID AS COTAÇÕES, FORMAT(tbl_Cotacoes.Data_Emiss_Cotac, 'dd/mm/yyyy') AS [DT COT], ";
        $sql .= "tbl_Compradores.NOME_COMPRAD AS COMPRADOR, FORMAT(tbl_Cotacoes.Data_Grade, 'dd/mm/yyyy') AS [DT GRADE], tbl_Cotacoes.Usuario_Grade AS [USUAR GRADE] ";
        $sql .= "FROM tbl_Compradores ";
        $sql .= "RIGHT JOIN tbl_Cotacoes ON tbl_Compradores.usuario = tbl_Cotacoes.Usuario_Cotac ";
        $sql .= "WHERE tbl_Compradores.ID=" . (int) $idComprador . " ";
        $sql .= $andSql;
        $sql .= "ORDER BY tbl_Compradores.NOME_COMPRAD ASC, tbl_Cotacoes.Data_Emiss_Cotac ASC, tbl_Cotacoes.ID ASC";
        $res = $this->objDb->query($sql);
        $cotacoes = array();
        foreach ($res as $cotacao) {
            $cotacoes[] = $cotacao;
        }
        return $cotacoes;
    }
    public function getCompradores ()
    {
        $sql = "SELECT tbl_Compradores.ID, tbl_Compradores.NOME_COMPRAD ";
        $sql .= "FROM tbl_Compradores ";
        $sql .= "ORDER BY tbl_Compradores.NOME_COMPRAD;";
        $res = $this->objDb->query($sql);
        $compradores = array();
        foreach ($res as $comprador) {
            $compradores[] = $comprador;
        }
        return $compradores;
    }
    public function getDiretores ()
    {
        $sql = "SELECT Setor.Sigla, Setor.Responsavel AS RESPONSÁVEL ";
        $sql .= "FROM Setor ";
        $sql .= "WHERE Setor.Cod_Analitico_Setor='14' ";
        $sql .= "OR Setor.Cod_Analitico_Setor='15' ";
        $sql .= "OR Setor.Cod_Analitico_Setor='151' ";
        $sql .= "OR Setor.Cod_Analitico_Setor='152' ";
        $sql .= "OR Setor.Cod_Analitico_Setor='153' ";
        $sql .= "OR Setor.Cod_Analitico_Setor='16' ";
        $sql .= "OR Setor.Cod_Analitico_Setor='161' ";
        $sql .= "OR Setor.Cod_Analitico_Setor='162';";
        $res = $this->objDb->query($sql);
        $diretores = array();
        foreach ($res as $diretor) {
            $diretores[] = $diretor;
        }
        return $diretores;
    }
    public function getRequisicoesCotacao ($idCotacao)
    {
        $sql = "SELECT tbl_Req_Compras.ID, tbl_Req_Compras.Ano, Right('00000' & [Num_req],5) & '/' & [ano] AS REQUIS, ";
        $sql .= "	FORMAT(tbl_Req_Compras.Data_Emiss, 'dd/mm/yyyy') AS [DT EMISS], tbl_Req_Compras.Ident_Cotacoes ";
        $sql .= "FROM tbl_Req_Compras ";
        $sql .= "WHERE (((tbl_Req_Compras.Ident_Cotacoes) = " . $idCotacao . ")) ";
        $sql .= "ORDER BY tbl_Req_Compras.Ano, Right('00000' & [Num_req],5) & '/' & [ano]; ";
        $res = $this->objDb->query($sql);
        $requisicoes = array();
        foreach ($res as $requisicao) {
            $requisicoes[] = $requisicao;
        }
        return $requisicoes;
    }
    public function getRequisicoesPendentes ()
    {
        $sql .= "SELECT tbl_Req_Compras.ID, tbl_Req_Compras.Ano, Right('00000' & [Num_req],5) & '/' & [ano] AS REQUIS, tbl_Req_Compras.Data_Emiss AS [DT EMISS], tbl_Req_Compras.Data_Distr_Compr, tbl_Req_Compras.Ident_Comprador, tbl_Req_Compras.Ident_Cotacoes ";
        $sql .= "FROM tbl_Req_Compras ";
        $sql .= "WHERE (((tbl_Req_Compras.Data_Distr_Compr) Is Not Null) And ((tbl_Req_Compras.Ident_Comprador) = [cmb_diretoria]) And ((tbl_Req_Compras.Ident_Cotacoes) Is Null) And ((tbl_Req_Compras.Data_Cancel) Is Null)) ";
        $sql .= "ORDER BY tbl_Req_Compras.Ano, Right('00000' & [Num_req],5) & '/' & [ano];";
        $res = $this->objDb->query($sql);
        $requisicoes = array();
        foreach ($res as $requisicao) {
            $requisicoes[] = $requisicao;
        }
        return $requisicoes;
    }
    public function getCompradoresRequisicoes ()
    {
        $sql = "SELECT tbl_Compradores.ID, tbl_Compradores.NOME_COMPRAD ";
        $sql .= "FROM tbl_Compradores ";
        $sql .= "ORDER BY tbl_Compradores.NOME_COMPRAD;";
        $res = $this->objDb->query($sql);
        $compradores = array();
        foreach ($res as $comprador) {
            $compradores[] = $comprador;
        }
        return $compradores;
    }
    public function saveComentario ($idReq, $strComentario, $currentUser)
    {
        Zend_Loader::loadClass('Zend_Date');
        $sql = "SELECT COUNT(tbl_Memo.ident_tab) AS qtd ";
        $sql .= "FROM tbl_Memo ";
        $sql .= "WHERE (((tbl_Memo.nome_tab)='requisições') AND ((tbl_Memo.ident_tab)=" . $idReq . "));";
        $res = $this->objDb->fetchRow($sql);
        if (count($res) && $res['qtd'] > 0) {
            // pt_BR format
            Zend_Date::setOptions(array('format_type' => 'php'));
            $objDate = new Zend_Date();
            $date = $objDate->toString('j/n/Y'); // 'dia sem zeros'/'mes sem zeros'/'ano 4 digitos'
            $time = $objDate->toString('H:i:s'); // 'hora com zeros':'minutos com zeros':'segundos com zeros' 
            $strComentario = chr(13) . chr(10) . "--------------------" . chr(13) . chr(10) . $date . " - " . $time . " " . strtoupper($currentUser) . ": " . $strComentario;
            $sql = "UPDATE tbl_Memo ";
            $sql .= "SET memo = memo + '" . $strComentario . "' ";
            $sql .= "WHERE (((tbl_Memo.nome_tab)='requisições') AND ((tbl_Memo.ident_tab)=" . $idReq . "));";
            if ($this->objDb->query($sql)) {
                return $strComentario;
            }
        }
        return 0;
    }
    public function getRequisicaoCorrecao ($numRequisicao)
    {
        $sql = "SELECT tbl_Req_Compras.ID, [Num_req] & '/' & [ano] AS requ, Right('00000' & [Num_req],5) & '/' & [ano] AS REQUIS, ";
        $sql .= "FORMAT(tbl_Req_Compras.Data_Emiss, 'dd/mm/yyyy') AS [Data_Emiss], tbl_Req_Compras.Requisitante, tbl_Req_Compras.corrigir ";
        $sql .= "FROM tbl_Req_Compras ";
        $sql .= "WHERE ((([Num_req] & '/' & [ano])='" . $numRequisicao . "') AND ((tbl_Req_Compras.corrigir)=False)); ";
        $res = $this->objDb->fetchRow($sql);
        return $res;
    }
    public function updateCorrigirRequisicao ($idRequisicaoCompra, $corrigir)
    {
        $sql = "UPDATE tbl_Req_Compras SET ";
        $sql .= "corrigir = " . $corrigir . " ";
        $sql .= "WHERE ID = " . $idRequisicaoCompra;
        $res = $this->objDb->query($sql);
        if ($res) {
            return true;
        } else {
            throw new Exception($sql);
        }
    }
    public function saveCorrecaoRequisicao ($idRequisicaoCompra)
    {
        $corrigir = true;
        $resCorrigir = $this->updateCorrigirRequisicao($idRequisicaoCompra, $corrigir);
        if ($resCorrigir) {
            $nomeTab = "requisições";
            $identTab = $idRequisicaoCompra;
            Zend_Date::setOptions(array('format_type' => 'php'));
            $objDate = new Zend_Date();
            // pt_BR format
            $date = $objDate->toString('j/n/Y'); // 'dia sem zeros'/'mes sem zeros'/'ano 4 digitos'
            $time = $objDate->toString('H:i:s'); // 'hora com zeros':'minutos com zeros':'segundos com zeros' 
            $sql = "SELECT tbl_Memo.nome_tab, tbl_Memo.ident_tab, tbl_Memo.memo ";
            $sql .= "FROM tbl_Memo ";
            $sql .= "WHERE (((tbl_Memo.nome_tab)='requisições') AND ((tbl_Memo.ident_tab)=" . $idRequisicaoCompra . ")); ";
            $res = $this->objDb->fetchRow($sql);
            if (count($res) && $res['ident_tab'] > 0) {
                $memo = $res['memo'] . chr(13) . chr(10) . "Retornou para correção em " . $date . " - " . $time;
                $sql = "UPDATE tbl_Memo SET ";
                $sql .= "nome_tab = '" . $nomeTab . "', ";
                $sql .= "ident_tab = " . $identTab . ", ";
                $sql .= "memo = '" . $memo . "' ";
                $sql .= "WHERE nome_tab = 'requisições' AND ident_tab = " . $idRequisicaoCompra;
            } else {
                $memo = "Retornou para correção em " . $date . " - " . $time;
                $sql = "INSERT INTO tbl_Memo(";
                $sql .= "nome_tab, ident_tab, memo";
                $sql .= ")VALUES(";
                $sql .= "'" . $nomeTab . "', ";
                $sql .= $identTab . ", ";
                $sql .= "'" . $memo . "'";
                $sql .= ")";
            }
            $res = $this->objDb->query($sql);
            if ($res) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function getHistoricoComentariosRequisicao ($idReq)
    {
        $sql = "SELECT tbl_Memo.nome_tab, tbl_Memo.ident_tab, tbl_Memo.memo ";
        $sql .= "FROM tbl_Memo ";
        $sql .= "WHERE (((tbl_Memo.nome_tab)='requisições') AND ((tbl_Memo.ident_tab)=" . $idReq . ")) ;";
        $res = $this->objDb->fetchRow($sql);
        return $res;
    }
    public function getItensRequisicao ($coluna = null, $valor = null)
    {
        if (strlen($valor) && ((int) $valor == 0)) {
            $valor = "'" . $valor . "'";
        }
        $sqlWhere = (strlen($coluna) && strlen($valor)) ? "WHERE " . $coluna . " = " . $valor . " " : "";
        $sql = "SELECT tbl_Req_Compr_Detalhes.Item, tbl_Req_Compr_Detalhes.cod_Produto, ";
        $sql .= "tbl_Req_Compr_Detalhes.Desc_mat, tbl_Req_Compr_Detalhes.quantidade, ";
        $sql .= "tbl_Req_Compr_Detalhes.unidade, tbl_Req_Compr_Detalhes.Ident_req_Compras, ";
        $sql .= "tbl_Req_Compr_Detalhes.Ident_Contratos, tbl_Req_Compras.Ident_Cotacoes ";
        $sql .= "FROM tbl_Req_Compras ";
        $sql .= "RIGHT JOIN tbl_Req_Compr_Detalhes ";
        $sql .= " ON tbl_Req_Compras.ID = tbl_Req_Compr_Detalhes.Ident_req_Compras ";
        $sql .= $sqlWhere;
        $sql .= "ORDER BY tbl_Req_Compr_Detalhes.Item";
        //return $sql;exit;
        $res = $this->objDb->query($sql);
        $itensRequisicao = array();
        foreach ($res as $itemRequisicao) {
            $itensRequisicao[] = $itemRequisicao;
        }
        return $itensRequisicao;
    }
    public function getContratosCotacao ($idCotacao)
    {
        $sql = "SELECT tbl_Contratos.ID, tbl_Contratos.Ident_Cotacoes, tbl_Contratos_Tipos.Tipos_Contratos AS TIPO, ";
        $sql .= "Format([NUMERO],'0000') & '/' & [ANO] AS CONTRATO, tbl_Contratos.Data_Emiss_Contrato AS [DT EMISS] ";
        $sql .= "FROM tbl_Tipos_Mod ";
        $sql .= "RIGHT JOIN (tbl_Contratos_Tipos ";
        $sql .= "	RIGHT JOIN tbl_Contratos ";
        $sql .= "	ON tbl_Contratos_Tipos.ID = tbl_Contratos.Ident_Tipo_Contrato) ";
        $sql .= "ON tbl_Tipos_Mod.ID = tbl_Contratos.Ident_Tipo_Mod ";
        $sql .= "WHERE (tbl_Contratos.Ident_Cotacoes=" . $idCotacao . ");";
        $res = $this->objDb->fetchAll($sql);
        $contratos = array();
        foreach ($res as $contrato) {
            $contratos[] = $contrato;
        }
        return $contratos;
    }
    public function updateCompradorRequisicao ($dataDistrCompr, $idComprador, $idRequisicaoCompra)
    {
        $idComprador = ((int) $idComprador > 0) ? $idComprador : NULL;
        $dataDistrCompr = ($dataDistrCompr) ? "Date()" : NULL;
        $sql = "UPDATE tbl_Req_Compras SET ";
        $sql .= "tbl_Req_Compras.Data_Distr_Compr = " . $dataDistrCompr . ", ";
        $sql .= "tbl_Req_Compras.Ident_Comprador = " . $idComprador . " ";
        $sql .= "WHERE (((tbl_Req_Compras.ID)=" . $idRequisicaoCompra . "));";
        $res = $this->objDb->fetchAll($sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
    public function updateAprovCompra ($usuario, $siglaAprov, $idCotacao, $dataAprovComp = 'Date()')
    {
        $usuario = strlen(trim($usuario)) ? "'" . $usuario . "'" : "NULL";
        $siglaAprov = strlen(trim($siglaAprov)) ? "'" . $siglaAprov . "'" : "NULL";
        $dataAprovComp = is_null($dataAprovComp) ? "NULL" : $dataAprovComp;
        $sql = "UPDATE tbl_Cotacoes SET ";
        $sql .= "tbl_Cotacoes.Data_Aprov_Comp = " . $dataAprovComp . ", ";
        $sql .= "tbl_Cotacoes.Usuario_Aprov_Comp = " . $usuario . ", ";
        $sql .= "tbl_Cotacoes.sigla_aprov = " . $siglaAprov . " ";
        $sql .= "WHERE tbl_Cotacoes.ID = " . $idCotacao . ";";
        $res = $this->objDb->query($sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
    public function updateAprovCompraDir ($usuario, $idCotacao, $dataAprovCompDir = 'Date()')
    {
        $usuario = strlen(trim($usuario)) ? "'" . $usuario . "'" : "NULL";
        $dataAprovCompDir = is_null($dataAprovCompDir) ? "NULL" : $dataAprovCompDir;
        $sql = "UPDATE tbl_Cotacoes SET ";
        $sql .= "tbl_Cotacoes.Data_Aprov_Dir = " . $dataAprovCompDir . ", ";
        $sql .= "tbl_Cotacoes.Usuario_Aprov_Dir = " . $usuario . " ";
        $sql .= "WHERE tbl_Cotacoes.ID = " . $idCotacao;
        $res = $this->objDb->query($sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
}