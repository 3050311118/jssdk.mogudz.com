<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no">
<!-- <meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black"> -->

  <script src="https://unpkg.com/vue@2.1/dist/vue.min.js"></script>
  <script src="https://unpkg.com/vue-scroller@2.1/dist/vue-scroller.min.js"></script>
  <script src="http://static.mogudz.com/js/mqttws31.js"></script>
  <title>我的在线设备列表</title>
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
  <div class="header">
    <h1 class="title">下拉刷新列表</h1>
  </div>
  <scroller :on-refresh="refresh"
            ref="my_scroller" style="top: 44px;">
    <div v-for="(item, index) in items" class="row" :class="{'grey-bg': index % 2 == 0}">
    	<div v-if="index==0">点击打开设备页面</div>
	     <div  @click="choose(item.sn)" v-else>序列号{{item.sn}} 设备名{{item.nickname}}</div>
        </div>
  </scroller>
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
		    message = new Paho.MQTT.Message('{"action":"GETONLINE"}');
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
	            	app.items.push(json);
	            }catch(e){
	            }
	        };  
	    }catch(e){
	    }
	}
  var app=new Vue({
    el: '#app',
    components: {
      Scroller
    },
    data: {
      items: []
    },
    mounted: function () {
      mqtt(); 
      setInterval(function(){
	 if(isConnected===2)mqtt();
      },5000);
      setTimeout(() => {
        this.$refs.my_scroller.resize();
      })
    },
    methods: {
      choose:function(index){
	   alert(""+index);
      },
      refresh: function () {
// 	mqtt();
//      this.items=[];      	
        setTimeout(() => {
          this.$refs.my_scroller.finishPullToRefresh();	  	
        }, 3000)
      }
    }
  });
</script>
</body>
</html>
