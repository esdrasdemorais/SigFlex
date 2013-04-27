// Cotações ActionScript file

private function btnCotacoes_click():void
{
	//Define o modo de exibição
	modo = 3;
	//Desabilita os controles que não serão usados
	optRequisicoes.enabled=true;
	optContratos.enabled=true;
	chkGrade.enabled=true;
	dtfDtInicial.enabled=true;
	//Exibe a caixa de controle adequada
	ctbCompradores.visible=true;
	ctbMensagem.visible=false;
	ctbDiretoria.visible=false;
	//Exibe Grid principal de Requisição de Compra
	grdPrincipal.visible   = true;
	//Oculta o textarea de Histórico de Comentários
	txaHistorico.visible   = false;
	//Ajusta o controle para a data de hoje
	dtfDtInicial.selectedDate= new Date;
	//Nomeia listas
	lblLista1.text='COTAÇÕES POR COMPRADOR A PARTIR DE ' + 
	String(dtfDtInicial.selectedDate.day) + '/' +
	String(dtfDtInicial.selectedDate.month) + '/' +
	String(dtfDtInicial.selectedDate.fullYear);
	lblLista2.text='REQUISIÇÕES ';
	resizeLabel();

	// Seta o Label do Grid Principal
	DataGridColumn(grdPrincipal.columns[0]).headerText='COTAÇÕES';
	DataGridColumn(grdPrincipal.columns[1]).headerText='DT COT';
	DataGridColumn(grdPrincipal.columns[2]).headerText='COMPRADOR';
	DataGridColumn(grdPrincipal.columns[3]).headerText='DT GRADE';
	// Seta o Nome dos campos da SQL retornada para os valores do Grid Principal
	DataGridColumn(grdPrincipal.columns[0]).dataField='COTAÇÕES';
	DataGridColumn(grdPrincipal.columns[1]).dataField='DT COT';
	DataGridColumn(grdPrincipal.columns[2]).dataField='COMPRADOR';
	DataGridColumn(grdPrincipal.columns[3]).dataField='DT GRADE';
	
	// Seta o Label do Grid Complementar
	DataGridColumn(grdRequisicoes.columns[0]).headerText='REQUIS';
	DataGridColumn(grdRequisicoes.columns[1]).headerText='DT EMISS';
	DataGridColumn(grdRequisicoes.columns[2]).headerText='DT Ident_Cotacoes';
	// Seta o Nome dos campos da SQL retornada para os valores do Grid Principal
	DataGridColumn(grdRequisicoes.columns[0]).dataField='REQUIS';
	DataGridColumn(grdRequisicoes.columns[1]).dataField='DT EMISS';
	DataGridColumn(grdRequisicoes.columns[2]).dataField='Ident_Cotacoes';

	// Limpar Data Grid Principal
	//grdPrincipal.dataProvider = undefined;
	
	/*If IsNull(Me.cmb_diretoria) Then
    	MsgBox "Escolha um comprador", vbCritical, UCase(CurrentUser())
	End If*/

	//Alert.show("Count compradores="+cboCompradores.dataProvider.length);

	//if(cboCompradores.dataProvider.length = 0)
	//{
	//Alert.show("Depois=\nRowCoun="+grdPrincipal.rowCount+"\ndataProvider.length="+cboCompradores.dataProvider.length);
	//}
	
	cboDiretoria.enabled = true;
	cboDiretoria.setFocus();
	
	if(dtfDtInicial.text.length > 0)
	{
		var comGrade:Boolean = false;
		
	  	comGrade = (chkGrade.selected == true) ? true : false;
	 
		//Alert.show(cboCompradores.selectedItem.ID + " - " + dtfDtInicial.text + " - " + comGrade);
	  	
	  	//modelControle.getCotacoes($dataEmissaoCotacao, $comDataGrade)
	  	modelControle.getCotacoes(cboCompradores.selectedItem.ID, dtfDtInicial.text, comGrade);
 	}
 
 	if(grdPrincipal.selectedIndex != -1 && grdPrincipal.selectedItem.ID > 0)
 	{
    	modelControle.getRequisicoesCotacao(grdPrincipal.selectedItem.ID);
  	}
}

/* Manipuladores de eventos padrão para correta execução e depuração do Remote Object */
private function resultHandlerCompradores(event:ResultEvent):void
{
	cboCompradores.dataProvider = event.result;
	cboCompradores.labelField   = "NOME_COMPRAD";
}

private function resultHandlerCotacoes(event:ResultEvent):void
{
	grdPrincipal.dataProvider = event.result;
}

private function resultHandlerRequisicoesCotacao(event:ResultEvent):void
{
	grdRequisicoes.dataProvider = event.result;
}

private function resultHandlerContratosCotacao(event:ResultEvent):void
{
	grdRequisicoes.dataProvider = event.result;	
}

