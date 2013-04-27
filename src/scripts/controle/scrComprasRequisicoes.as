// ActionScript file

private function btnRequisicoes_click():void
{
	// requisicoes();
	// Retorna a matriz contendo os registros
	modelControle.getRequisicoes();

	//Define o modo de exibição
	modo = 1;
	//Desabilita os controles que não serão usados
	optRequisicoes.enabled = false;
	optContratos.enabled   = false;
	chkGrade.enabled       = false;
	dtfDtInicial.enabled   = false;
	//Exibe a caixa de controle adequada
	ctbCompradores.visible = true;
	ctbMensagem.visible    = false;
	ctbDiretoria.visible   = false;
	//Exibe Grid principal de Requisição de Compra
	grdPrincipal.visible   = true;
	//Oculta o textarea de Histórico de Comentários
	txaHistorico.visible   = false;

    //Nomeia as listas
	lblLista1.text='REQUISIÇÕES PENDENTES: ';
	lblLista2.text='REQUISIÇÕES ';
	
	grdPrincipal.dataProvider 	= null;
	grdRequisicoes.dataProvider = null;
	//
	//DataGridColumn(grdPrincipal.columns[0]).headerText='ID';
	//DataGridColumn(grdPrincipal.columns[1]).headerText='Ano';
	DataGridColumn(grdPrincipal.columns[0]).headerText='REQUIS';
	DataGridColumn(grdPrincipal.columns[1]).headerText='DT EMISS';
	DataGridColumn(grdPrincipal.columns[2]).headerText='DT SUPRIM';
	DataGridColumn(grdPrincipal.columns[3]).headerText='REQUISITANTE';
	
	//
	//DataGridColumn(grdPrincipal.columns[0]).dataField='ID';
	//DataGridColumn(grdPrincipal.columns[1]).dataField='Ano1';
	DataGridColumn(grdPrincipal.columns[0]).dataField='REQUIS';
	DataGridColumn(grdPrincipal.columns[1]).dataField='DT EMISS';
	DataGridColumn(grdPrincipal.columns[2]).dataField='DT SUPRIM';
	DataGridColumn(grdPrincipal.columns[3]).dataField='Requisitante';
	//
	//DataGridColumn(grdPrincipal.columns[0]).visible = false;
	//DataGridColumn(grdPrincipal.columns[1]).visible = false;
}

// Evento inicial a executar o retorno do Remote Object 
public function resultHandlerPrincipal(event:ResultEvent):void
{
	//Alert.show(ObjectUtil.toString(event.result));
	
	// Popula o grid principal
	grdPrincipal.dataProvider 	  = event.result;
	lblListaPrincipalCounter.text = grdPrincipal.dataProvider.length;
}

// Carrega Grid Auxiliar
private function resultHandlerRequisicoes(event:ResultEvent):void
{
	grdRequisicoes.dataProvider = event.result;
}

