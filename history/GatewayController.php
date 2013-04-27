<?php
class GatewayController extends Zend_Controller_Action
{
 
    public function init()
	{
        //remove a renderização do layout da página
        $this->getHelper('viewRenderer')->setNoRender();
        //carrega a classe
        Zend_Loader::loadClass('Zend_Amf_Server');
    }
 
    public function amfAction()
	{
        //criamos a instancia do componente Zend AMF
        $server = new Zend_Amf_Server();
		
		$server->addDirectory(APPLICATION_PATH . '/application/models/');
		
        echo($server->handle());
    }
 
}