var net = require('net');
var fs = require('fs');
var dgram = require('dgram');

var WebSocketServer = require("ws").Server; 
var wss = new WebSocketServer( { port: 8100 } ); 

var HOST = '192.168.1.40';
var PORT = 21100 


var HOST2 = '192.168.1.10';
var PORT2 = 25000;

var HOST3 = '192.168.1.30';
var PORT3 = 6000;

var i=0;
var ct = 0
var filter1 = "";
var filter2 = "";

var counter = 0;

console.log('Server listening on ' + HOST +':'+ PORT);
//console.log('salut');
wss.on('connection', function (ws) {
	var last_known_port_pic;
	
	var udpClient = dgram.createSocket('udp4');
	console.log("lancement du process websocket");
	
		
	 net.createServer(function(sock) { 
		
		console.log('CONNECTED GW : ' + sock.remoteAddress +':'+ sock.remotePort);
		
		sock.on("data", function(data) {
			//console.log(counter);

			var canStr = data.toString('hex');
			
			if(canStr.length >72){
				sock.write('received "' + data + '"');
				var nbFrame = canStr.length/72;
				for(i=0;i<nbFrame;i++){
					var mult = i*72;
					var dlc = canStr.substring(42+mult, 44+mult);
					var canId = canStr.substring(48+mult, 56+mult);
					var canData = canStr.substring(56+mult, 72+mult);
					
					switch(dlc){
						case '01':
							canData = canData.substring(0,2);
							break;
						case '02':
							canData = canData.substring(0,4);
							break;
						case '03':
							canData = canData.substring(0,6);
							break;
						case '04':
							canData = canData.substring(0,8);
							break;
						case '05':
							canData = canData.substring(0,10);
							break;
						case '06':
							canData = canData.substring(0,12);
							break;
						case '07':
							canData = canData.substring(0,14);
							break;
						case '08':
							canData = canData.substring(0,16);
							break;

						default:
							return
					}
					
					if(canStr.substring(48,49)==9){
						canId = 1+canStr.substring(49, 56); 
					}else if(canStr.substring(48,49)==8){
						canId = 0+canStr.substring(49, 56);
					}
					
					var jsonData = '{"type":"from_GW","canId":"'+canId+'", "canData":"'+canData+'"}';					

					ws.send(jsonData);
										
					counter ++;
				}
			}else{
				//console.log("frame per packet : "+ (canStr.length/72));
				var dlc = canStr.substring(42, 44);
				var canId = canStr.substring(48, 56);
				var canData = canStr.substring(56, 72);
				
				switch(dlc){
					case '01':
						canData = canData.substring(0,2);
						break;
					case '02':
						canData = canData.substring(0,4);
						break;
					case '03':
						canData = canData.substring(0,6);
						break;
					case '04':
						canData = canData.substring(0,8);
						break;
					case '05':
						canData = canData.substring(0,10);
						break;
					case '06':
						canData = canData.substring(0,12);
						break;
					case '07':
						canData = canData.substring(0,14);
						break;
					case '08':
						canData = canData.substring(0,16);
						break;

					default:
						return
				}
				
				if(canStr.substring(48,49)==9){
					canId = 1+canStr.substring(49, 56); 
				}else if(canStr.substring(48,49)==8){
					canId = 0+canStr.substring(49, 56);
				}
				
				var jsonData = '{"type":"from_GW", "canId":"'+canId+'", "canData":"'+canData+'"}';
								
				sock.write('received "' + data + '"');
				
				ws.send(jsonData);
									
				counter ++;
			}
		});
		
		sock.on('close', function(data) {
			console.log('CLOSED: ' + sock.remoteAddress +' '+ sock.remotePort);
		});		

	
		//	envoi du soft vers l'ip
		ws.on('message', function incoming(data) {
			var message = JSON.parse(data);
			console.log(message.type);
			
			if(message.type == "signal"){
				var msgBuff = new Buffer(message.msg, 'hex');
				
				udpClient.send(msgBuff, 0, 36, PORT2, HOST2);
				console.log(message.msg);
			}
			
		});
		
	}).listen(PORT);	
	
	// Communication with the pic 
	 
	net.createServer(function(soc) {
 
    // We have a connection - a socket object is assigned to the connection automatically
    console.log('CONNECTED PIC : ' + soc.remoteAddress +':'+ soc.remotePort);
	last_known_port_pic = soc.remotePort;
	soc.write("DC");  
    // Add a 'data' event handler to this instance of socket
    soc.on('data', function(data) { 
        console.log('DATA ' + soc.remoteAddress + ': ' + data);
		var dataStr = data.toString();
		
		var slf = dataStr.substring(0, 4);
		var enf = dataStr.substring(4, 8);
		var slv = dataStr.substring(8, 10); 
		var env = dataStr.substring(10, 12);
		var srtl = dataStr.substring(12, 14);
		var globv = dataStr.substring(14, 16);
		var tsuiv = dataStr.substring(16, 18);
		var hasSRTL = dataStr.substring(18, 19);

		
		var jsonData = '{"type":"from_pic", "slf":"'+slf+'", "enf":"'+enf+'", "slv":"'+slv+'", "env":"'+env+'", "srtl":"'+srtl+'","globv":"'+globv+'", "tsuiv":"'+tsuiv+'", "has_srtl":"'+hasSRTL+'"}';
		
		console.log(jsonData);
		
		ws.send(jsonData);        
	});
	
	ws.on('message', function incoming(data) {
			var message = JSON.parse(data);
			console.log(message.type);
			console.log('msg ; '+message.msg);

	
			if(message.type == "signal_pic"){				
				soc.write(message.msg);
				console.log(message.msg);
			}
		});
 
    // Add a 'close' event handler to this instance of socket
    soc.on('close', function(data) {
        console.log('CLOSED: ' + soc.remoteAddress +' '+ soc.remotePort);
    });
 
}).listen(PORT3);

});



