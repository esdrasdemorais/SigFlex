// Ap Cotações ActionScript file
import flash.events.MouseEvent;

import mx.controls.Alert;
import mx.rpc.events.ResultEvent;
import mx.utils.ArrayUtil;


private function btnApCotacoes_click():void
{
	//Define o modo de exibição
	modo = 2;
	//Desabilita os controles que não serão usados
	optRequisicoes.enabled = false;
	optContratos.enabled   = false;
	chkGrade.enabled       = false;
	dtfDtInicial.enabled   = false;
	cboDiretoria.enabled   = true;
	//Exibe a caixa de controle adequada
	ctbCompradores.visible = false;
	ctbMensagem.visible    = false;
	//cboCompradores.visible = false;
	ctbDiretoria.visible   = true;
	//Exibe Grid principal de Requisição de Compra
	grdPrincipal.visible   = true;
	//Oculta o textarea de Histórico de Comentários
	txaHistorico.visible   = false;

	//Nomeia listas
	lblLista1.text='COTAÇÕES PENDENTES: ';
	lblLista2.text='PEND. APROV. ';

	// Limpar Data Grid Principal
	grdPrincipal.dataProvider = undefined;
		
	// Seta o Label do Grid Principal
	DataGridColumn(grdPrincipal.columns[0]).headerText='COTAÇÕES';
	DataGridColumn(grdPrincipal.columns[1]).headerText='DT EMISS';
	DataGridColumn(grdPrincipal.columns[2]).headerText='DT GRADE';
	DataGridColumn(grdPrincipal.columns[3]).headerText='TOTAL';
	// Seta o Nome dos campos da SQL retornada para os valores do Grid Principal
	DataGridColumn(grdPrincipal.columns[0]).dataField='COTAÇÕES';
	DataGridColumn(grdPrincipal.columns[1]).dataField='DT EMISS';
	DataGridColumn(grdPrincipal.columns[2]).dataField='DT GRADE';
	DataGridColumn(grdPrincipal.columns[3]).dataField='TOTAL';
	
	// Limpar Data Grid Secundário
	grdRequisicoes.dataProvider = null;
	
	// Seta o Label do Grid Complementar
	DataGridColumn(grdRequisicoes.columns[0]).headerText='COTAÇÕES';
	DataGridColumn(grdRequisicoes.columns[1]).headerText='DT EMISS';
	DataGridColumn(grdRequisicoes.columns[2]).headerText='DT APROV';
	// Seta o Nome dos campos da SQL retornada para os valores do Grid Principal
	DataGridColumn(grdRequisicoes.columns[0]).dataField='COTAÇÕES';
	DataGridColumn(grdRequisicoes.columns[1]).dataField='DT EMISS';
	DataGridColumn(grdRequisicoes.columns[2]).dataField='DT APROV';
	
	// Carrega os dados do grid principal contendo os dados de aprovação de cotações
	modelControle.getApCotacoes();

	// Carrega os dados das cotacoes pendentes de aprovação
	//Alert.show("val="+cboDiretoria.value+"\n"+cboDiretoria.selectedItem);
	carregaApCotacoesPendentesAprovacao();
}

private function carregaApCotacoesPendentesAprovacao():void
{
	if(cboDiretoria.selectedIndex != -1 && cboDiretoria.selectedItem.Sigla.length > 0)
	{
		//Alert.show("sigla="+cboDiretoria.selectedItem.Sigla);
		modelControle.getApCotacoesPendentesAprovacao(cboDiretoria.selectedItem.Sigla);
	}
}

private function resultHandlerDiretores(event:ResultEvent):void
{
	//Alert.show(ObjectUtil.toString(event.result));
	cboDiretoria.dataProvider = event.result;
	cboDiretoria.labelField   = "RESPONSÁVEL";
}

private function resultHandlerApCotacoes(event:ResultEvent):void
{
	//Alert.show("result="+event.result[0].COTAÇÕES + "\n" + event.result[0].TOTAL);
	grdPrincipal.dataProvider = event.result;
}

private function resultHandlerApCotacoesPendentesAprovacao(event:ResultEvent):void
{
	//Alert.show(event.result.toString());
	grdRequisicoes.dataProvider = event.result;	
}

private function btnAprovar_Click(event:MouseEvent):void
{
	aprovCompra();
}

private function resultHandlerUpdateAprovCompra(event:ResultEvent):void
{
	if(event.result.toString().length > 0)
	{
		//Alert.show("Aprovação atualizada com sucesso.\n"+event.result.toString());
		// Atualiza GRIDS
		btnApCotacoes_click();
	}
}

public function aprovCompra():void
{
	/*Alert.show('\n\dir='
	+ObjectUtil.toString(cboDiretoria.selectedItem)
	+'\nidCot='+ObjectUtil.toString(grdPrincipal.selectedItem));*/
	
    if(cboDiretoria.selectedIndex != -1 && grdPrincipal.selectedIndex != -1)
    {
		// Alterar para puxar usuario do login
	    // Controle -> updateAprovCompra($usuario, $siglaAprov, $idCotacao)
    	modelControle.updateAprovCompra(
    		__model.usuarioVO.usLogin, 
    		cboDiretoria.selectedItem.Sigla, 
    		grdPrincipal.selectedItem.ID
    	);
    	
    	// Atualiza GRIDS
    	btnApCotacoes_click();
    }
    else if(grdPrincipal.selectedIndices.length == 0)
    {
        Alert.show("Não há registros!"); //CurrentUser()
    }
    else
    {
		Alert.show("Escolha um Diretor ou Gerente!"); //CurrentUser()
        cboDiretoria.setFocus();
    }
}