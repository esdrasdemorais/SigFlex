package com.business.delegates
{
    /**
    * LoginDelegate
    *  Passes the users credentials as a value object tothe service.
    * */
    public class LoginDelegate
	{
	    import mx.rpc.IResponder;
	    import com.vo.UsuarioVO;
	    import com.adobe.cairngorm.business.ServiceLocator;
	
	    private var responder:IResponder;
	    private var service:Object;
	
	    /**
	    * Initilizes the service call
	    * @param IResponder responder
	    * @return nothing
	    * */
	    public function LoginDelegate( responder:IResponder ) {
		    this.responder = responder;
		    this.service = ServiceLocator.getInstance().getRemoteObject("UsuarioService");
	    }
	
	    /**
	    * Command that actually calls the service and addsthe responder mapping to the service method call
	    * @param UsuarioVO login
	    * @return void
	    * */
	    public function verifyUser(login:UsuarioVO):void {
		    var call:Object = service.checkLdapLogin( login );
		    call.addResponder( responder );
	    }
    }
}