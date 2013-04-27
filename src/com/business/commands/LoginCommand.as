package com.business.commands
{
    import com.adobe.cairngorm.commands.ICommand;
    import com.adobe.cairngorm.control.CairngormEvent;
    import com.business.delegates.LoginDelegate;
    import com.business.events.LoginEvent;
    import com.model.ModelLocator;
    import com.vo.UsuarioVO;
    
    import mx.controls.Alert;
    import mx.rpc.IResponder;
    import mx.utils.ObjectUtil;

    public class LoginCommand implements ICommand, IResponder
    {
	    public var __model:ModelLocator = ModelLocator.getInstance();
	
	    public function LoginCommand() { }
	
	    public function execute(event:CairngormEvent):void
	    {
		    var loginEvent:LoginEvent = event as LoginEvent;
		    var delegate:LoginDelegate = new LoginDelegate( this );
		    delegate.verifyUser(loginEvent.userVO);
	    }

	    public function result(event:Object):void
	    {
	    	//Alert.show(event.result.toString());
		    if(event.result)
		    {
			    if(event.result == "FAILURE_CREDENTIAL_INVALID")
			    {
			    	Alert.show("Descupe! Seu nome de usuário ou senha estão incorretos." +
			    				"Favor tente novamente ou acesse o sistema em modo somente leitura.");
			   	 	return;
		    	}
			    
			    var usuarioVO:UsuarioVO = event.result as UsuarioVO;
			    var accessPrivs:Object 	= usuarioVO.acessPrivilegesVO;
			    
			    if(accessPrivs["viewRestrictedUI"] == "allowed")
			    {
			    	// guarda o objeto com os dados do usuário logado na ModelLocator
			    	__model.usuarioVO = usuarioVO;
			    	//Alert.show(ObjectUtil.toString(__model.usuarioVO));
			    
				    // set default privileges
				    __model.supergranted = false;
				    __model.admingranted = false;
				    __model.MAINNAV.addItem({label: "Control Panel"});
				    __model.workflowState = ModelLocator.CONTROLPANEL;
				    __model.CURRENT_USER_ROLE = accessPrivs["userRole"];
				    __model.CURRENTSESSIONID = accessPrivs["sessionID"];
				    
				    if(__model.CURRENT_USER_ROLE == "Super") {
					    __model.supergranted = true;
					    __model.admingranted = true;
				    } else if(__model.CURRENT_USER_ROLE == "admin") {
					    __model.supergranted = false;
					    __model.admingranted = true;
				    }
			    } else {
				    __model.workflowState = ModelLocator.PUBLIC;
			    }
			    
			    // DEBUG MODE
			    trace("[User Login] – user Role:" + accessPrivs["userRole"]);
			    trace("[User Login] – viewPublicUI:" + accessPrivs["viewPublicUI"]);
			    trace("[User Login] – viewRestrictedUI:" + accessPrivs["viewRestrictedUI"]);
			    trace("[User Login] – createManager:" + accessPrivs["createManager"]);
			    trace("[User Login] – viewLogs:" + accessPrivs["viewLogs"]);
		    }
	    }

	    public function fault(event:Object):void
	    {
	    	mx.controls.Alert.show("fault=\n"+event.toString());
	    	trace("[User Login] – Error Connecting!" + event.toString());
	    }
    }
}