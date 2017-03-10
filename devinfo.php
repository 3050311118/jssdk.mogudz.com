<!DOCTYPE html>
<html>
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

	function mqtt(){ 
	    try 
	    {	
	        client = new Paho.MQTT.Client(location.hostname, 8083, "WEB"+userid);//
	        client.onConnectionLost = onConnectionLost;
	        client.onMessageArrived = onMessageArrived;
	        client.connect({onSuccess:onConnect});
	        function onConnect() {
	            client.subscribe(userid+"/PUB");
      		    isConnected=1;
      		    message = new Paho.MQTT.Message('{"action":"GETINFO"}');
      		    message.destinationName = userid+"/SUB";
      		    client.send(message);  
	        };
	        function onConnectionLost(responseObject) {
	 	         isConnected=2;
	        };
	        function onMessageArrived(message) {
	            var str=message.payloadString;
	            try{
	            	var json=JSON.parse(str);
			    app.isdhcp=json.isDhcp;
			    app.mode=json.mode;
			    app.staip=json.staip;
			    app.stagateway=json.stagateway;
			    app.stanetmask=json.stanetmask;
			    app.stadns=json.stadns;
			    app.apip=json.apip;
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
    },
    methods: {
      choose:function(index){
	        window.location.href="/devinfo.php?id="+index;
      }
    }
  });
</script>
</body>
</html>
