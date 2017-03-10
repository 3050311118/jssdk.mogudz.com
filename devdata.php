<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no">

  <script src="https://unpkg.com/vue@2.1/dist/vue.min.js"></script>
  <script src="http://static.mogudz.com/js/mqttws31.js"></script>
  <title>设备配置信息</title>
  <style>
    html, body {
      margin: 0;
    }
    * {
      box-sizing: border-box;
    }
    .row {
      width: 100%;
      height: 50px;
      padding: 10px 0;
      font-size: 16px;
      line-height: 30px;
      text-align: center;
      color: #444;
      background-color: #fff;
    }
    .grey-bg {
      background-color: #eee;
    }
    .header {
      position: fixed;
      top: 0;
      left: 0;
      height: 44px;
      width: 100%;
      box-shadow: 0 2px 10px 0 rgba(0,0,0,0.1);
      background-color: #fff;
      z-index: 1000;
      color: #666;
    }
    .header > .title {
      font-size: 16px;
      line-height: 44px;
      text-align: center;
      margin: 0 auto;
    }
  </style>
</head>
<body>
<div id="app">
	{{isdhcp}} <br> {{mode}} <br>{{staip}} <br>{{stagateway}}<br>{{stanetmask}}<br>{{stadns}}<br>{{apip}}
</div>
<script>
	var client;	
	var message;
	var isConnected=0;
 	var userid="<?php echo $_GET["id"];?>";  
	
	function pub(){
		client.send(message); 
	}
	function mqtt(){ 
	    try 
	    {	
	        message = new Paho.MQTT.Message('{"action":"GETPARA"}');
      		message.destinationName = userid+"/SUB";
	        client = new Paho.MQTT.Client(location.hostname, 8083, "WEB"+userid);//
	        client.onConnectionLost = onConnectionLost;
	        client.onMessageArrived = onMessageArrived;
	        client.connect({onSuccess:onConnect});
	        function onConnect() {
	            client.subscribe(userid+"/PUB");
      		    isConnected=1;
			pub();
	        };
	        function onConnectionLost(responseObject) {
	 	         isConnected=2;
	        };
	        function onMessageArrived(message) {  	
                    try{
                        var payload = message.payloadBytes;
                        var data = new Uint16Array(payload);
                        alert(data[0])
                    }catch(e){
                    }
	        };  
	    }catch(e){
	    }
	}
  var app=new Vue({
    el: '#app',
    data: {
	isdhcp:'',
	mode:'',
	staip:'',
	stagateway:'',
	stanetmask:'',
	stadns:'',
	apip:''
    },
    mounted: function () {
      mqtt(); 
    }
  });
</script>
</body>
</html>
