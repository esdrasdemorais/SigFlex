<?php
class SecurityPlugin extends Zend_Controller_Plugin_Abstract
{
	/**
	 * @uses : verifica se o usuário esta autenticado e caso
	 * não esteja redireciona para o controller de login
	 *
	 * @author : Diego Tremper
	 * 
	 * @copyright : http://blog.diegotremper.com/archives/34/3
	 * 
	 * @param Zend_Controller_Request_Abstract $request
	 */

	public function routeShutdown(Zend_Controller_Request_Abstract $oRequest) 
	{
		$sControllerName = $oRequest->getControllerName();
		$sActionName = $oRequest->getActionName();
		
		$oAuth = Zend_Auth::getInstance();
		
		if(strtolower($sControllerName) != 'login' && !$oAuth->hasIdentity()) 
		{
			$oFrontController = Zend_Controller_Front::getInstance();
			$sBaseUrl = $oFrontController->getBaseUrl();
			
			$sRedirect = urlencode($sControllerName.'/'.$sActionName);
			
			$this->getResponse()->setRedirect($sBaseUrl . '/login/index?redirect='.$sRedirect, 302);
		}
	}
}