<?php
class MessageBrokerController extends Zend_Controller_Action
{
	public function init()
	{
		//renderiza a saída, devidamente formatada no protocolo AMF
		Zend_Loader::loadClass('Zend_Amf_Server');
		Zend_Loader::loadClass('Zend_Session');
		
		$this->getHelper('viewRenderer')->setNoRender();
		
		//Instancia o servidor PHP
		//$server = new Zend_Amf_Server();
		
		//Adiciona o diretório php para que as classes sejam encontradas
		//$server->addDirectory(dirname(__FILE__) ."/../models/");
		
		//echo $server->handle();
		//$this->view->handle = trim($server->handle()); 
		//echo ($this->view->handle);exit;
		
		//$this->_helper->layout->disableLayout();
		//$this->_helper->viewRenderer->setNoRender();
	}
	 
	public function amfAction()
	{
		$objServer = new Zend_Amf_Server();
		$objServer->addDirectory(APPLICATION_PATH.'/models/');
		$objServer->addDirectory(APPLICATION_PATH.'/services/');
		$objServer->setProduction(false);
		
		Zend_Session::start();
		
		// $server->setSession();
		if($objServer->isSession())
		{
			$objServer->setSession();
		}
		
		// Generate a new sessionid for each browser session, helps prevent session hijacking
		// This also prevents "PHP Notice:  Undefined index:  PHPSESSID ", from being given in the php log file.
		Zend_Session::regenerateId();

		echo $objServer->handle();
	}
	
	public function testeAction()
	{
		$modelControle = new Controle();
		
		echo "<pre>";
		//echo($modelControle->salvarComentario(14774, 'Coment�rio teste Esdrinhas'));
		//print_r($modelControle->getHistoricoComentariosRequisicao(14774));
		//print_r($modelControle->getApCotacoes());
		//print_r($modelControle->getApCotacoesPendentesAprovacao('DirTec'));
		//print_r($modelControle->getItensRequisicao("Ident_Cotacoes", 13706));
		//print_r($modelControle->saveCorrecaoRequisicao(13240));
		//print_r($modelControle->getCompradores());
		//print_r($modelControle->getRequisicoesCotacoes());
		//print_r($modelControle->getCotacoes(2, '01/01/2009', false));
		
		$modelUsuario = new Usuario();
		$usuarioVO = new UsuarioVO();
		#$usuarioVO->setUsLogin('edneitonelli');
		#$usuarioVO->setUsSenha('');
	
		#print_r($modelUsuario->checkLdapLogin($usuarioVO));
		#print_r($modelControle->getCotacoesPendentesAprovacaoDir('DirPre'));
		#echo $modelControle->getCotacoesAprovadasDir('DirPre');
		print_r($modelControle->getApCotacoes());
		
        #print_r($modelControle->getDiretores());
		
		$reqCompras = new ReqCompras();
		#print_r($reqCompras->cslReqCompras(14774));

		echo "</pre>";
		exit;
	}
}