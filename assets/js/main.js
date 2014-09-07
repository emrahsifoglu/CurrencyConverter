//jQuery.noConflict();
$(document).ready(function() {
	
	function DoListener(event, operation, textStatus, result) {
		switch(event.type) {
		
			case 'Done' 	: 
				
				if (operation == "exchange"){
					if(textStatus == "true") {  
						var from = result.from;
						var to = result.to;			
						Log(from.amount + " " + from.currency + "is " + to.amount + " " + to.currency);
					} else {
						Log("An error caused because of : " + result);
					}
				}
			
			break;
			
			case 'Undone'	: Log(operation.status + " " + textStatus + " " + result);
			break;
		}
		
	}
	
	function Do(data) {
		$.ajax({
			url : "ajax.php",
			type: "POST",
			data : data,
			beforeSend: function(jqXHR, setting) {
				Log("beforeSend"); //jqXHR.abort();
				$(document).bind('Undone', DoListener);
				$(document).bind('Done', DoListener);
			},
			success: function(data, textStatus, jqXHR) {
				Log(textStatus);
				var obj = $.parseJSON(data);
				$(document).trigger('Done', [obj.operation, obj.succeed, obj.result]);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				Log(textStatus);
				$(document).trigger('Undone', [jqXHR, textStatus, errorThrown]);
			},
			complete: function(data){
				Log('completed');
				$(document).unbind('Done', DoListener);
				$(document).unbind('Undone', DoListener);
			}
		});
	}

	Do({operation: 'exchange', 'Amount' : 450, 'From' : 'USD', 'To' : 'TND'});
	
});