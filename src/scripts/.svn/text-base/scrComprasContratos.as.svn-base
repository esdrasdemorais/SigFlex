// ActionScript file
import mx.controls.Alert;
import mx.controls.dataGridClasses.DataGridColumn;
import mx.events.ListEvent;
import mx.olap.aggregators.CountAggregator;
import mx.rpc.events.FaultEvent;
import mx.rpc.events.ResultEvent;
import mx.utils.ObjectUtil;

// Manipuladores de eventos padrão para correta execução e depuração do Remote Object
public function faultHandler(event:FaultEvent):void
{
	//
	Alert.show(event.message.toString());
}

private function onError(event:FaultEvent):void
{
	Alert.show(event.fault.faultString + "\n" + event.fault.faultDetail + "\n" + event.fault.faultCode, 'Erro');
}