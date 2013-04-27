package com.business.commands
{
	import com.adobe.cairngorm.commands.ICommand;
	import com.adobe.cairngorm.control.CairngormEvent;
	import com.business.delegates.GetNewSIDDelegate;
	import com.business.events.GenerateSIDEvent;
	import com.model.ModelLocator;

	import mx.rpc.IResponder;

	/**
	 * GenerateSIDCommand
	 *
	 *
	*/
	public class GenerateSIDCommand implements ICommand, IResponder
	{
		public var __model:ModelLocator = ModelLocator.getInstance();

		/**
		* Constructor
		*
		* @param none
		*
		*
		*/
		public function GenerateSIDCommand()
		{
		}

		/**
		* Calls the delegate to make a request to the server
		*
		* @param CairngormEvent event
		*
		* @return void
		*
		*/
		public function execute(event:CairngormEvent):void
		{
			var getNewSIDEvent:GenerateSIDEvent = event as GenerateSIDEvent;
			var delegate:GetNewSIDDelegate = new GetNewSIDDelegate( this );
			delegate.getNewSID();
		}

		/**
		* Recieves and processes the result from the delegate
		*
		* @param Object event
		*
		* @return void
		*
		*/
		public function result(event:Object):void {
			if(event.result) {
				__model.CURRENTSESSIONID = event.result;
			}
		}

		/**
		* Handles any errors received by the delegate returned from the server
		*
		* @param Object event
		*
		* @return void
		*
		*/
		public function fault(event:Object):void {
			trace("[GenerateNewSID] - Error Connecting!" + event.toString());
		}

	}
}