<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wx5de1ab48fc31328f", "5bccc90e29a6dbfa5cf795bb88477d22");
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>小聪科技wifi配置</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="bootstrap.min.css">
  <link rel="stylesheet" href="airkiss.css">
  <script type="text/javascript" src="jquery-1.8.3.min.js"></script>
  <script type="text/javascript" src="airkiss.js"></script>
  <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body ontouchstart="">
  <div class="box" style="overflow: hidden;">
    <img alt="" src="gitwislogo.png" class="logo">
    <div style="margin-top: 10%">此操作将为你的设备配置WiFi网络,</div>
    <div>请将手机接入可用WiFi并在下一步操作中</div>
    <div>输入此WiFi密码</div>  
    <img alt="" src="wifibtn.png" class="connectWifi">    
  </div>
      <span class="desc">开始airkiss</span>
      <button class="btn btn_primary" id="startRecord">开始</button>
</body>
<script>
  wx.config({
    beta: true,
    // debug: true,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
  jsApiList: [
    'configWXDeviceWiFi',
    ]
  });
wx.ready(function() {
  $(".connectWifi").click(function(){
    wx.invoke('configWXDeviceWiFi', {}, function(res) {
      if (res.err_msg == 'configWXDeviceWiFi:ok') {
        alert("配置成功");
        WeixinJSBridge.call('closeWindow');
      } else if (res.err_msg == 'configWXDeviceWiFi:fail') {
        alert("配置失败");
      }
      console.log(JSON.stringify(res));
    });
  });
});
wx.error(function(res) {
  alert(res.errMsg);
});

</script>
<script src="zepto.min.js"></script>
<script src="demo.js"> </script>
</html>
