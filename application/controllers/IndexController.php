<?php
/**
 * Controlador Default
 * @author Flávio Gomes da Silva Lisboa
 * @copyright FGSL 2008
 * @license New SDB
 * @package application
 * @subpackage controllers
 * @filesource 
 */
class IndexController extends Zend_Controller_Action 
{
	/**
	 * Inits
	 * 
	 */
	function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
		$this->view->actionName 	= $this->getRequest()->getActionName();
		$this->view->controllerName	= $this->getRequest()->getControllerName();
		if(Zend_Auth::getInstance() && Zend_Auth::getInstance()->hasIdentity() && Zend_Auth::getInstance()->hasIdentity())
		{
			$this->view->userLogin	= @Zend_Auth::getInstance()->getIdentity()->us_login;
			$this->view->userUnitId	= @Zend_Auth::getInstance()->getIdentity()->un_id;
			$this->view->userUnit 	= @Zend_Auth::getInstance()->getIdentity()->un_descricao;
			$this->view->userId 	= @Zend_Auth::getInstance()->getIdentity()->us_id;
			$this->view->userTipoId = @Zend_Auth::getInstance()->getIdentity()->tu_id;
		}
	}

	/**
	 * Método default
	 *
	 */
	public function indexAction()
	{
		$this->view->title = "Página Inicial";
    	$this->view->detail = "in IndexController::indexAction() at " . $this->view->baseUrl;
		$this->render();
		#echo "<pre>";
		#print_r($this->view);
		#echo "</pre>";
		#exit;
	}
	
	function listuserAction()
    {
    	$this->view->title = "Lista de usuários";
    	$this->view->detail = "in IndexController::listuserAction() at " . $this->view->baseUrl;
		
    	if ( $this->_request->isPost() )
    	{
    		/*
    		Zend_Loader::loadClass('Zend_Filter_StripTags');
    		$filter = new Zend_Filter_StripTags();
    		
    		$id = (int)$this->_request->getPost('id');
    		$artist = $filter->filter($this->_request->getPost('artist'));
    		$artist = trim($artist);
    		$title = $filter->filter($this->_request->getPost('title'));
    		$title = trim($title);    		
    		
    		if ($id !== false) 
    		{
    		    if ( $artist != '' && $title != '' )
	    		{
	    			$data = array(
	    				'artist' => $artist,
	    				'title' => $title
	    			);
	    			
	    			$where = 'id = ' . $id;
	    			
	    			$album->update($data, $where);
	    			
	    			$this->_redirect('/index/edit/id/' . $id);
	    			return;
	    		}
	    		else 
	    		{
	    			$this->view->album = $album->fetchRow('id = ' . $id);
	    		}
    		}
    		*/
    		echo "Is post";
    	}
		else
		{
			$id = $this->_request->getParam('id', 0);
			if ($id > 0) {
				echo "vish... funciona mesmo e o id é=" . $id;
			}
		}
    }
}
?>