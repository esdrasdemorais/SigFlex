package com.business.delegates
{
	/**
	 * GetNewSIDDelegate
	 *
	 * Makes a request to the server service to re-genearate a users SessionID
	 *
	 */
	public class GetNewSIDDelegate
	{
		import mx.rpc.IResponder;
		import com.adobe.cairngorm.business.ServiceLocator;

		private var responder : IResponder;
		private var service : Object;

		/**
		* Initilizes the service call
		*
		* @param IResponder responder
		*
		* @return nothing
		*
		*/
		public function GetNewSIDDelegate( responder:IResponder ) {
			this.responder = responder;
			this.service = ServiceLocator.getInstance().getRemoteObject("LoginService");
		}

		/**
		* Command that actually calls the service and adds the responder mapping to the service method call
		*
		* @param nothing
		*
		* @return void
		*
		*/
		public function getNewSID():void {
			var call:Object = service.generateNewSessionID();
			call.addResponder( responder );
		}
	}
}