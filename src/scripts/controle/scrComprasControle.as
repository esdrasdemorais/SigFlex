// Genérico ActionScript file

import com.business.events.ReportEvent;
import com.model.ModelLocatorReport;
import com.view.reports.FlexReportDemo;
import com.view.reports.custom.PreviewWindow;
import com.view.reports.dataproviders.DPTemplatePadrao;
import com.view.reports.demo.DemoReport;
import com.view.reports.templates.RelRequisicaoCompra;

import mx.collections.ArrayCollection;
import mx.controls.Alert;
import mx.rpc.events.ResultEvent;
import mx.utils.ArrayUtil;
import mx.utils.ObjectUtil;

import org.doc.Document;

private var modo:int = 0;

[Bindable] public var arrItenRequisicao:ArrayCollection = null;

private function onError(event:FaultEvent):void
{
	Alert.show(event.fault + "\n\n" + event.message + "\n\n" + event.fault.faultString + "\n\n" + 
				event.fault.faultDetail + "\n\n" + event.fault.faultCode + "\n\n" + event.fault + "\n\n" + 
				event.message + "\n\n" + event.fault.faultString + "\n\n" + event.fault.faultDetail + "\n\n" + 
				event.fault.faultCode + "\n\n" + event.fault.rootCause, 
				'Erro');
}

public function faultHandler(event:FaultEvent):void
{
	// Exibe o Erro
	Alert.show(event.fault + "\n\n" + event.message + "\n\n" + event.fault.faultString + "\n\n" + 
				event.fault.faultDetail + "\n\n" + event.fault.faultCode + "\n\n" + event.fault.rootCause, 
				'Erro RemoteObject');
}

private function dtfDtInicial_onchange():void
{
	//Acrescenta a data no título da lista principal
	lblLista1.text='COTAÇÕES POR COMPRADOR A PARTIR DE ' + dtfDtInicial.text;
}

private function init():void
{
	// Carrega o combo com os Diretores 
	modelControle.getDiretores();
	// Carrega o combo com os Compradores
	modelControle.getCompradores();
}

// Option que carrega as REQUISIÇÕES
private function optRequisicoes_click():void
{
	//Acrescenta o nome do comprador no título da lista
	lblLista2.text = 'REQUISIÇÕES (' + cboCompradores.text + ')';
	//lblLista2.text = 'REQUISIÇÕES (' + cboDiretoria.text + ')';
	
	modelControle.getRequisicoesCotacao(grdPrincipal.selectedItem.ID);
}

private function optContratos_click():void
{
	//Acrescenta o nome do comprador no título da lista
	lblLista2.text = 'CONTRATOS (' + cboCompradores.text + ')';
	//lblLista2.text = 'CONTRATOS (' + cboDiretoria.text + ')';
	
	if(grdPrincipal.selectedIndex != -1)
	{
		modelControle.getContratosCotacao(grdPrincipal.selectedItem.ID);
	}
}

private function resultHandlerItensRequisicao(event:ResultEvent):void
{
	//Alert.show("result=\n"+ObjectUtil.toString(event.result));
	arrItenRequisicao = new ArrayCollection(ArrayUtil.toArray(event.result));
	//Alert.show("arrItenRequisicao=\n"+ObjectUtil.toString(arrItenRequisicao));
	//grdItensRequisicao.dataProvider = event.result;
}

/**
 * Função Genérica para carregar o GRID Principal de acordo com o botão de navegação clicado 
 * (Requisições, ApCotações, Correções ou Cotações)
 */
private function onChangePrincipal(event:ListEvent):void
{
	// Seta o Título das Colunas do Grid
	DataGridColumn(grdItensRequisicao.columns[0]).headerText='Item';
	DataGridColumn(grdItensRequisicao.columns[1]).headerText='Cód. Produto';
	DataGridColumn(grdItensRequisicao.columns[2]).headerText='Descrição';
	DataGridColumn(grdItensRequisicao.columns[3]).headerText='QTD';
	DataGridColumn(grdItensRequisicao.columns[4]).headerText='UND';
	DataGridColumn(grdItensRequisicao.columns[5]).headerText='Ident_Contratos';
	DataGridColumn(grdItensRequisicao.columns[6]).headerText='Ident_Cotacoes';
	
	// Seta o nome do campo das Colunas
	DataGridColumn(grdItensRequisicao.columns[0]).dataField='Item';
	DataGridColumn(grdItensRequisicao.columns[1]).dataField='cod_Produto';
	DataGridColumn(grdItensRequisicao.columns[2]).dataField='Desc_mat';
	DataGridColumn(grdItensRequisicao.columns[3]).dataField='quantidade';
	DataGridColumn(grdItensRequisicao.columns[4]).dataField='unidade';
	DataGridColumn(grdItensRequisicao.columns[5]).dataField='Ident_Contratos';
	DataGridColumn(grdItensRequisicao.columns[6]).dataField='Ident_Cotacoes';
    
	// Guarda o filtro de acordo com o tipo da ação
	var id:int = event.itemRenderer.data.ID;
	switch(modo)
	{
		// Requisições
        case 1:
            modelControle.getItensRequisicao("Ident_req_Compras", id);
        	break;
        // Ap Cotações
	    case 2:
	        modelControle.getItensRequisicao("Ident_Cotacoes", id);
			break;
		// Cotações
	    case 3:
			modelControle.getItensRequisicao("Ident_Cotacoes", id);
			modelControle.getRequisicoesCotacao(id);
			break;
	}
}

/**
 * 	Função Genérica para carregar o GRID secundário de acordo com o botão de navegação clicado 
 * (Requisições, ApCotações, Correções ou Cotações)
 */
private function onChangeRequisicoes(event:ListEvent):void
{
	//Alert.show('Id Row selecionada = ' + grdRequisicoes.selectedIndex + " - " + event.itemRenderer.data.ID);
	//Alert.show(event.itemRenderer.data.ID);
	var id:int = event.itemRenderer.data.ID;
	switch(modo)
	{
	    case 1:
	    	//REQUISIÇÕES
	        /*If var_list <> 6 Then
	            Me.Filho8.LinkMasterFields = "lista2"
	            Me.Filho8.LinkChildFields = "Ident_req_Compras"
	            var_list = 6
	        End If*/
	        break;
	    case 2:
	    	//AP COTAÇÕES
	        modelControle.getItensRequisicao("Ident_Cotacoes", id);
	        break;
	    case 3:
	    	//REQUISIÇÕES
	        /*If var_list <> 8 Then
	            If Me.Opção43 = True Then
	                Me.Filho8.LinkMasterFields = "lista2"
	                Me.Filho8.LinkChildFields = "Ident_req_Compras"
	            var_list = 6
	            Else
	                Me.Filho8.LinkMasterFields = "lista2"
	                Me.Filho8.LinkChildFields = "Ident_Contratos"
	            var_list = 8
	            End If
	        End If*/
	    case 4:
		    //CORREÇÕES
		    
		    //CARREGA O HISTÓRICO DE COMENTÁRIOS DA REQUISICAO SELECIONAEDA
			modelControle.getHistoricoComentariosRequisicao(event.itemRenderer.data.ID);
			
			//CARREGA OS ITES DA REQUISIÇÃO SELECIONADA
			modelControle.getItensRequisicao("Ident_req_Compras", event.itemRenderer.data.ID);
			
			break;
		default:
			/*If var_list <> 6 Then
	    	Me.Filho8.LinkMasterFields = "lista2"
	    	Me.Filho8.LinkChildFields = "Ident_req_Compras"
	    	var_list = 6
	    	End If*/
	}
}

/**
 * 	Função Genérica para carregar o GRID Principal no evento Duplo Click de acordo com o botão de navegação clicado 
 * (Requisições, ApCotações, Correções ou Cotações)
 */
private function onDoubleClickPrincipal(event:ListEvent):void
{
	//Alert.show(event.itemRenderer.data.ID);
    switch(modo)
    {
	    case 1:
	    	//REQUISIÇÕES
	        if(cboCompradores.value.length == 0)
	        {
	            Alert.show("Escolha um comprador!"); //CurrentUser()
	            cboCompradores.setFocus();
	        }
	        else if(grdPrincipal.selectedIndices.length == 0)
            {
                Alert.show("Não há registros!"); //CurrentUser()
            }
        	else
        	{
        		Alert.show(dtfDtInicial.text + ", " + cboCompradores.value + ", " + grdPrincipal.selectedItem.ID);
  				
        		// Controle -> updateCompradorRequisicao($dataDistrCompr, $idComprador, $idRequisicaoCompra)
  				modelControle.updateCompradorRequisicao(dtfDtInicial.text, cboCompradores.selectedItem.ID, grdPrincipal.selectedItem.ID);
            }
	    	break;
	    case 2:
	    	//AP COTAÇÕES
	    	aprovCompra();
	    	break;
    }
}

private function onDoubleClickRequisicoes(event:ListEvent):void
{
	//Alert.show('' + event.itemRenderer.data.ID);
	
 	switch(modo)
  	{
		//REQUISIÇÕES
	    case 1:
	        if(!(grdRequisicoes.rowCount > 0))
	        {
	            Alert.show("Não há registros!"); //UCase(CurrentUser())
	        }
	        else
	        {
            	// Seta como NULL o Ident_Comprador e a Data_Distr_Compr
            	modelControle.updateCompradorRequisicao(null, null, grdRequisicoes.selectedItem.ID);
	        }
	        break;
	  	//AP COTAÇÕES
	    case 2:
	        if(!(grdRequisicoes.rowCount > 0))
	        {
	        	Alert.show("Não há registros!"); //UCase(CurrentUser())
	        }
	        else
	        {
                // Seta como NULL o Usuario e a siglaAprov
               modelControle.updateAprovCompra(null, null, grdRequisicoes.selectedItem.ID, null);
	        }
	        break;
		//COTAÇÕES
	  	case 4:
	  		//CORREÇÃO
	  		showConfirmaRetiradaRequisicao();		
	    	/*Me.Texto51 = Null*/
	    	break;
	}
}

/**
 * ********************************************** Funções para o Relatório **********************************************		
 */		
// criando uma variável do tipo ModelLocatorReport para guardar a instância do ModelLocatorReport
[Bindable] private var __modelLocatorReport:ModelLocatorReport = ModelLocatorReport.getInstance(); // nunca = new ModelLocatorReport()

[Bindable] private var doc:Document = null;
private function showReport(event:ReportEvent):void
{
	var janela:FlexReportDemo = new FlexReportDemo();
	
	janela.width  = this.width;
	janela.height = this.height;
	janela.addEventListener(CloseEvent.CLOSE, closeVisualizarImpressao);
	
	//var source:DemoReportDP = new DemoReportDP();
	//var source:DemoReportDP = new DemoReportDP(event.dados);
	var report:DemoReport 	= new DemoReport();
 	
 	//doc = new Document(report, source, PaperFormat.A4);
 	
 	//doc.pdfScript = "http://www.kemelyon.com/flexreport/create.php";
 	//doc.pdfEnabled = true;
	//doc.title = "Flex Report Demo";

	Alert.show(ObjectUtil.toString(janela.printPreview));
	
	__modelLocatorReport.dados = event.dados;
	
	Alert.show('Aqui guardou na modelLocatorReport\n\n'+ObjectUtil.toString(__modelLocatorReport.dados));
	
	PopUpManager.addPopUp(janela, this, true);
	
	//janela.printPreview.doc = doc;
}

private function visualizarImpressao(event:MouseEvent):void
{
	var janela:FlexReportDemo = new FlexReportDemo();
	
	janela.width  = this.width;
	janela.height = this.height;
	janela.addEventListener(CloseEvent.CLOSE, closeVisualizarImpressao);
	
	PopUpManager.addPopUp(janela, this, true);
	
	//this.addChildAt(new FlexReportDemo(), 1);
	
	this.addEventListener('visualizarRelatorio', showReport);
	
	// criando um novo evento com o nome avisaLogin do tipo Event
	var evento:ReportEvent	= new ReportEvent('visualizarRelatorio');
	evento.dados = this.grdRequisicoes.dataProvider as ArrayCollection;
	
	//Alert.show(ObjectUtil.toString(evento.dados));
	
	// disparando um evento com o nome (tipo) visualizarRelatorio do tipo Event
	dispatchEvent(evento);
	
	Alert.show('ak!// add o ouvinte do evento\n'+evento);
}

private function closeVisualizarImpressao(event:CloseEvent):void
{
	PopUpManager.removePopUp(this);
}

private function btnVisualizar_click(event:MouseEvent):void//event:MouseEvent
{
	/*
 	doc.pdfScript = "http://www.kemelyon.com/flexreport/create.php";
 	doc.pdfEnabled = true;
	doc.title = "Flex Report Correções";
	
	Alert.show(ObjectUtil.toString(doc));
	*/
	
	//Alert.show(ObjectUtil.toString(janela.preview.doc));
	
	switch(modo)
	{
    	case 1:
	    	//REQUISIÇÕES
	        /*If var_list = 1 Then
	                 If IsNull(Me.Lista0) Then
	                     MsgBox "Não Há registro SELECIONADO!", vbCritical, UCase(CurrentUser())
	                 Else
	                        stDocName = "rel_Requisicao_Compra"
	                        DoCmd.OpenReport stDocName, acPreview, , "tbl_Req_Compras.ID = " & Me.Lista0
	                 End If
	        End If
	        If var_list = 6 Then
	                 If IsNull(Me.Lista2) Then
	                     MsgBox "Não Há registro SELECIONADO!", vbCritical, UCase(CurrentUser())
	                 Else
	                        stDocName = "rel_Requisicao_Compra"
	                        DoCmd.OpenReport stDocName, acPreview, , "tbl_Req_Compras.ID = " & Me.Lista2
	                 End If
	        End If*/
        	break;
    	case 2:
        	//
        	/*If var_list = 2 Then
                 If IsNull(Me.Lista0) Then
                     MsgBox "Não Há registro SELECIONADO!", vbCritical, UCase(CurrentUser())
                 Else
    
                    stDocName = "csl_Grade_Compar_Aprov"
                    stDocName1 = "rel_Grade"
                    
                    DoCmd.OpenQuery stDocName
                    Numer_Emresa
                    DoCmd.OpenReport stDocName1, acViewPreview
                     
                 End If
        	End If
	        If var_list = 7 Then
	                 If IsNull(Me.Lista2) Then
	                     MsgBox "Não Há registro SELECIONADO!", vbCritical, UCase(CurrentUser())
	                 Else
	                    stDocName = "csl_Grade_Compar_Aprov2"
	                    stDocName1 = "rel_Grade"
	                    
	                    DoCmd.OpenQuery stDocName
	                    Numer_Emresa
	                    DoCmd.OpenReport stDocName1, acViewPreview
	                 End If
	        End If*/
	     	break;
		case 3:
			//
	        /*If var_list = 3 Then
	                 If IsNull(Me.Lista0) Then
	                     MsgBox "Não Há registro SELECIONADO!", vbCritical, UCase(CurrentUser())
	                 Else
	                     stDocName = "rel_Cotação"
	                     DoCmd.OpenReport stDocName, acPreview, , "tbl_cotacoes.ID = " & Me.Lista0
	                 End If
	        End If
	        If var_list = 6 Then
	                 If IsNull(Me.Lista2) Then
	                     MsgBox "Não Há registro SELECIONADO!", vbCritical, UCase(CurrentUser())
	                 Else
	                        stDocName = "rel_Requisicao_Compra"
	                        DoCmd.OpenReport stDocName, acPreview, , "tbl_Req_Compras.ID = " & Me.Lista2
	                 End If
	        End If
	        If var_list = 8 Then
	                 If IsNull(Me.Lista0) Then
	                     MsgBox "Não Há registro SELECIONADO!", vbCritical, UCase(CurrentUser())
	                 Else
	                        stDocName = "rel_Ordem_Compra"
	                        DoCmd.OpenReport stDocName, acPreview, , "tbl_Contratos.ID = " & Me.Lista2
	                 End If
	        End If*/
	        break;
    	case 4:
    		// Relatório de Requisição de Compra
			if(grdRequisicoes.selectedIndex == -1)
			{
				Alert.show('Não há registro selecionado!', 'UCase(CurrentUser()');
			}
			else
			{
				//stDocName = "rel_Requisicao_Compra"
				//DoCmd.OpenReport stDocName, acPreview, , "tbl_Req_Compras.ID = " & Me.Lista2
				serviceReqCompras.cslReqCompras(grdRequisicoes.selectedItem.ID);
			}
			break;
    }
}

//[Bindable] private var doc:Document = null;
[Bindable] private var janela:PreviewWindow  	  = null;
[Bindable] private var dp:DPTemplatePadrao 	 	  = null;
[Bindable] private var report:RelRequisicaoCompra = null;

import org.doc.Document;
import org.print.Preview;
import com.view.reports.templates.RelRequisicaoCompra;

private function resultHandlerClsReqCompras(event:ResultEvent):void
{
	/*
	 * FlexReport exemplo tirado de Elvis Fernandes
	 * http://www.elvis.eti.br/2009/02/25/gerando-relatorios-com-o-flexreport-parte-2-screencast/
	 */
	janela = new PreviewWindow();
	janela.width  = this.width;
	janela.height = this.height;
	
	dp = new DPTemplatePadrao();
	
	/*var arrDados:ArrayCollection = new ArrayCollection();
	arrDados.source = event.result as Array;
	dp.tabela = arrDados;*/
	
	dp.tabela = new ArrayCollection(ArrayUtil.toArray(event.result));
	
	//Alert.show("dp.tabela=\n"+ObjectUtil.toString(dp.tabela));
	
	report = new RelRequisicaoCompra();

	// Modelo, DataProvider, Tamanho, página
	var doc:Document = new Document(report, dp, PaperFormat.A4);
	//doc = new Document(report, dp, PaperFormat.A4);

	PopUpManager.addPopUp(janela, this, true);

	janela.preview.doc = doc;
}
/**
 * ********************************************** Funções para o Relatório **********************************************		
 */