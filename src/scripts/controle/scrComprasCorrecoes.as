// Correções ActionScript file
		
// Carrega Grid Auxiliar
private function resultHandlerRequisicoesPendentes(event:ResultEvent):void
{
	grdRequisicoes.dataProvider = event.result;
}

private function resultHandlerRequisicaoCorrecao(event:ResultEvent):void
{
    if(event.result)
    {
    	txtNumRequisicao.text = event.result.ID;
    	
		Alert.show("Deseja encaminhar ao suprimentos para correção?\n\r" + 
			"Requisição nº " + event.result.REQUIS + "\n\r" + 
     		"Emitida em " + event.result.Data_Emiss + "\n\r" + 
     		"Requisitante " + event.result.Requisitante,
     		"", //UCase(CurrentUser())
     		Alert.YES|Alert.NO,
     		this,
     		consultarCorrecaoHandler
     		);
    }
    else
    {
   		Alert.show("Não há requisição com o número informado ou esta requisição já encontra-se no Suprimentos."); 
        //vbCritical, UCase(CurrentUser()));
    }
}

private function resultHandlerSaveCorrecaoRequisicao(event:ResultEvent):void
{
	if(event.result)
	{
		Alert.show("Requisição Encaminhada para o Suprimentos com Sucesso.");
	}
	else
	{
		Alert.show("Erro!\n\t" + event.result);
	}
}

private function resultHandlerSalvarCompradorRequisicao(event:ResultEvent):void
{
	if(event.result.toString().length > 0)
	{
		Alert.show('Comprador salvo com sucesso.');	
	}
}

private function resultHandlerUpdateCompradorRequisicao(event:ResultEvent):void
{
	if(event.result.toString().length > 0)
	{
		Alert.show('Comprador salvo com sucesso.');	
	}
}

private function resultHandlerHistoricoComentariosRequisicao(event:ResultEvent):void
{
	//Alert.show('' + event.result.memo);
	txaHistorico.text = event.result.memo;
	
	//CARREGA OS ITES DA REQUISIÇÃO SELECIONADA
	//modelControle.getItensRequisicao(event.itemRenderer.data.ID);
}

private function resultHandlerCorrigirRequisicao(event:ResultEvent):void
{
	if(event.result.toString().length > 0)
	{
		// Atualiza Grid
		btnCorrecoes_click();
		
		Alert.show('Correção atualizada com sucesso.');
	}
}
/* Manipuladores de eventos padrão para correta execução e depuração do Remote Object */

private function consultarCorrecaoHandler(event:CloseEvent):void
{
	//Alert.show(""+event.detail+"-"+txtNumRequisicao.text);
	if(event.detail == Alert.YES)
	{
		modelControle.saveCorrecaoRequisicao(txtNumRequisicao.text);	
	}
}

/* ============================================== POPUP Enviar Requisição para Correção ==================================================== */
private var promptPopUpTitleWindow:TitleWindow;
private var txtNumRequisicao:TextInput;

private function consultarCorrecao(event:MouseEvent):void
{
	modelControle.getRequisicaoCorrecao(txtNumRequisicao.text);

	// processa remoção do popup após envio do número da requisição
	PopUpManager.removePopUp(promptPopUpTitleWindow);
}

private function titleWindow_close(event:CloseEvent):void
{
	PopUpManager.removePopUp(promptPopUpTitleWindow);
}

private function btnCorrigir_click():void
{
	var lblNumRequisicao:Label = new Label();
	lblNumRequisicao.text = "Qual o número da requisição que deseja marcar para o suprimentos corrigir?";//\n\r
	
	txtNumRequisicao 	  = new TextInput();
	txtNumRequisicao.text = "0/0000";//Formato do número da requisição: 

	var btnSalvarCorrecao:Button = new Button();
	btnSalvarCorrecao.label = "Enviar";
	btnSalvarCorrecao.addEventListener(MouseEvent.CLICK, consultarCorrecao);
	
	/*var btnCancelarCorrecao:Button = new Button();
	btnCancelarCorrecao.label = "Cancelar";
	btnCancelarCorrecao.addEventListener(CloseEvent.CLOSE, titleWindow_close);*/
	
	promptPopUpTitleWindow 		 = new TitleWindow();
	promptPopUpTitleWindow.title = ""; //UCase(CurrentUser())
	promptPopUpTitleWindow.showCloseButton = true;
    promptPopUpTitleWindow.addEventListener(CloseEvent.CLOSE, titleWindow_close); 
	
	/* Adiciona os Objetos ao PopUp */
	promptPopUpTitleWindow.addChildAt(lblNumRequisicao, 0);
	promptPopUpTitleWindow.addChildAt(txtNumRequisicao, 1);
	promptPopUpTitleWindow.addChildAt(btnSalvarCorrecao, 2);
	//promptPopUpTitleWindow.addChildAt(btnCancelarCorrecao, 3);
	
	PopUpManager.addPopUp(promptPopUpTitleWindow, this, true);
	PopUpManager.centerPopUp(promptPopUpTitleWindow);	
}
/* ============================================== POPUP Enviar Requisição para Correção ==================================================== */

private function resizeLabel():void
{
	//
	lblLista1.maxWidth=ctbLista1.width - 60;
	lblLista2.maxWidth=ctbLista2.width - 60;
	lblLista1.width=ctbLista1.width - 60;
	lblLista2.width=ctbLista2.width - 60;
}

private function btnCorrecoes_click():void
{
	//Define o modo de exibição
	modo = 4;
	//Desabilita os controles que não serão usados
	optRequisicoes.enabled = false;
	optContratos.enabled   = false;
	chkGrade.enabled       = false;
	dtfDtInicial.enabled   = false;
	//Exibe a caixa de controle adequada
	ctbMensagem.visible    = true;
	ctbCompradores.visible = false;
	ctbDiretoria.visible   = false;
	//Oculta Grid principal de Requisição de Compra
	grdPrincipal.visible   = false;
	//Exibe o textarea de Histórico de Comentários
	txaHistorico.visible   = true;
	
	DataGridColumn(grdRequisicoes.columns[0]).headerText='REQUIS';
	DataGridColumn(grdRequisicoes.columns[1]).headerText='DT EMISS';
	DataGridColumn(grdRequisicoes.columns[2]).headerText='NOME_COMPRAD';
	
	//
	DataGridColumn(grdRequisicoes.columns[0]).dataField='REQUIS';
	DataGridColumn(grdRequisicoes.columns[1]).dataField='DT EMISS';
	DataGridColumn(grdRequisicoes.columns[2]).dataField='NOME_COMPRAD';

	//Nomeia listas
	lblLista1.text='HISTÓRICO: ';
	
	// LISTA DE REQUISIÇÕES
	lblLista2.text='LISTA DE REQUISIÇÕES PENDENTES:';
	
	modelControle.getRequisicoesPendentesCorrecoes();
}

private function btnEnviarComentario_click(event:MouseEvent):void
{
	if(txaMensagem.text.length > 0)
	{
		var strComentario:String;
		
		//Alert.show("linha selecionada grdRequisicoes: " + grdRequisicoes.selectedIndex + " - " + grdRequisicoes.selectedItem.ID);
		
		if(txaMensagem.text.length > 0 && (grdRequisicoes.selectedIndex != -1 && grdRequisicoes.selectedItem.ID > 0))
		{
			//Alert.show("modelControle.saveComentario(" + grdRequisicoes.selectedItem.ID + ", " + txaMensagem.text + ");");
			
			strComentario = modelControle.saveComentario(grdRequisicoes.selectedItem.ID, txaMensagem.text, __model.usuarioVO.usLogin);
			
			if(strComentario.length > 0)
			{
				txaMensagem.text += strComentario;
			}
			else
			{
				Alert.show('Não há registro selecionado.');
			}
			
			// Descreve a classe
			var dt:XML = describeType(grdRequisicoes.selectedItem);
			trace(dt.toXMLString());
			
			// Retor informação sobre a classe
			var ci:Object = ObjectUtil.getClassInfo(grdRequisicoes.selectedItem);
			trace(ObjectUtil.toString(ci));
		}
		
		txaMensagem.text = '';
	}
	else
	{
		txaMensagem.setFocus();
		Alert.show('Informe um comentário.');
	}
}

// Método executado quando o servidor Zend AMF responde a requisição para Salvar o Comentário
private function resultHandlerSalvarComentario(event:ResultEvent):void
{
	if(event.result.toString().length > 0)
	{
		Alert.show('Comentário salvo com sucesso.');
		txaHistorico.text += event.result;
	}
	else
	{
		Alert.show('' + event.result);
	}
}

/* =========== POPUP Retirar Requisição da Correção ============ */
private var promptPopUpTitleWindowRetirarRequisicao:TitleWindow;

private function updateCorrecao(event:MouseEvent):void
{
	modelControle.updateCorrigirRequisicao(grdRequisicoes.selectedItem.ID, "false");

	// processa remoção do popup após envio do número da requisição
	PopUpManager.removePopUp(promptPopUpTitleWindowRetirarRequisicao);
}

private function titleWindowPopupRetirarRequisicao_close(event:CloseEvent):void
{
	PopUpManager.removePopUp(promptPopUpTitleWindowRetirarRequisicao);
}

private function showConfirmaRetiradaRequisicao():void
{
	var lblConfirmacao:Label = new Label();
	lblConfirmacao.text = "Deseja retirar a requisição " + grdRequisicoes.selectedItem.REQUIS + " do grupo de correções?"; //vbQuestion + vbYesNo, UCase(CurrentUser());  
	
	var btnSalvarCorrecao:Button = new Button();
	btnSalvarCorrecao.label = "Enviar";
	btnSalvarCorrecao.addEventListener(MouseEvent.CLICK, updateCorrecao);
	
	/*var btnCancelarCorrecao:Button = new Button();
	btnCancelarCorrecao.label = "Cancelar";
	btnCancelarCorrecao.addEventListener(CloseEvent.CLOSE, titleWindow_close);*/
	
	promptPopUpTitleWindowRetirarRequisicao 		 = new TitleWindow();
	promptPopUpTitleWindowRetirarRequisicao.title = ""; //UCase(CurrentUser())
	promptPopUpTitleWindowRetirarRequisicao.showCloseButton = true;
    promptPopUpTitleWindowRetirarRequisicao.addEventListener(CloseEvent.CLOSE, titleWindowPopupRetirarRequisicao_close); 
	
	/* Adiciona os Objetos ao PopUp */
	promptPopUpTitleWindowRetirarRequisicao.addChildAt(lblConfirmacao, 0);
	promptPopUpTitleWindowRetirarRequisicao.addChildAt(btnSalvarCorrecao, 1);
	
	PopUpManager.addPopUp(promptPopUpTitleWindowRetirarRequisicao, this, true);
	PopUpManager.centerPopUp(promptPopUpTitleWindowRetirarRequisicao);	
}
/* =========== POPUP Retirar Requisição da Correção ============ */