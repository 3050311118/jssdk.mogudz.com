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
	温度{{paraTemp}} <br> 
	湿度{{paraHumidity}} <br>
  速度{{paraSpeed}} <br>
  浓度{{paraConcen}}<br>
  压力{{paraPressure}}<br>
  光照度{{paraIllumination}}<br>
  运行时间{{paraRunTime}}
  设置时间{{setTime}}
  设置光照度{{setIllumination}}
  设置压力{{setPressure}}
  设置浓度{{setConcentration}}
  设置速度{{setSpeed}}
  设置温度{{setTemp}}
  设置湿度{{setHumidity}}
  设置周期{{setCycle}}
  设置段数{{setSec}}
  最小温度{{tempSetMin}}
  最大温度{{tempSetMax}}
  最大速度{{speedSetMax}}
  时间间隔{{timeIntervalMax}}
  最大浓度{{concentrationMax}}
  最大光照度{{illuminationMax}}
  报警值{{alarm}}
  机器类型{{machineType}}
</div>
<script>
	var client;	
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
	            client.subscribe(userid+"/DATA");
      		    isConnected=1;
	        };
	        function onConnectionLost(responseObject) {
	 	         isConnected=2;
	        };
	        function onMessageArrived(message) {  	
                    try{
                        var payload = message.payloadBytes;
                        var data = new Uint16Array(payload);
                          app.paraTemp  = data[0];
                          app.paraHumidity= data[1];
                          app.paraSpeed= data[2];
                          app.paraConcen= data[3];
                          app.paraPressure= data[4];
                          app.paraIllumination= data[5];
                          app.paraRunTime= data[6];
                          app.setTime= data[7];
                          app.setIllumination= data[8];
                          app.setPressure= data[9];
                          app.setConcentration= data[10];
                          app.setSpeed= data[11];
                          app.setHumidity= data[12];
                          app.setTemp = data[13];
                          app.setCycle = data[14];
                          app.setSec = data[15];
                          app.tempSetMin = data[16];
                          app.tempSetMax = data[17];
                          app.speedSetMax = data[18];
                          app.timeIntervalMax = data[19];
                          app.concentrationMax = data[20];
                          app.illuminationMax = data[21];
                          app.alarm = data[22];
                          app.machineType = data[23];
                    }catch(e){
                    }
	        };  
	    }catch(e){
	    }
	}
  var app=new Vue({
    el: '#app',
    data: {
      paraTemp:0,
      paraHumidity:0,
      paraSpeed:0,
      paraConcen:0,
      paraPressure:0,
      paraIllumination:0,
      paraRunTime:0,
      setTime:0,
      setIllumination:0,
      setPressure:0,
      setConcentration:0,
      setSpeed:0,
      setHumidity:0,
      setTemp :0,
      setCycle :0,
      setSec :0,
      tempSetMin :0,
      tempSetMax :0,
      speedSetMax :0,
      timeIntervalMax :0,
      concentrationMax :0,
      illuminationMax :0,
      alarm:0,
      machineType :0
    },
    mounted: function () {
      mqtt(); 
    }
  });
</script>
</body>
</html>
