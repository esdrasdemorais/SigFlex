package com.business.events
{
	import flash.events.Event;
	
	import mx.collections.ArrayCollection;
	
	public class ReportEvent extends Event
	{
		// criando uma propriedade que servirá para transportar os dados do relatório
		[Bindable] public var dados:ArrayCollection = new ArrayCollection();
		
		public function ReportEvent(type:String, bubbles:Boolean=false, cancelable:Boolean=false)
		{
			super(type, bubbles, cancelable);
		}
	}
}