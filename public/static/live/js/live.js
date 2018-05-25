/**
 * Created by Administrator on 2018/5/24/024.
 */
var wsUrl="ws://swoole.com:9501";
var websocket=new WebSocket(wsUrl);
websocket.onopen=function(evt){
}
websocket.onmessage=function(evt){
    push(JSON.parse(evt.data));
}
websocket.onclose=function (evt) {
    console.log('close success');
}
websocket.onerror=function(evt){
        console.log('some errors'+evt.data);
 }
 function push(data){
    var html='<div class="frame">';
    html+='<h3 class="frame-header">';
    html+='<i class="icon iconfont icon-shijian"></i>第'+data.type+'节 01：30';
    html+='</h3>';
    html+='<div class="frame-item">';
    html+='<span class="frame-dot"></span>';
    html+='<div class="frame-item-author">';
    if(data.logo){
        html+='<img src="'+data.logo+'" width="20px" height="20px" />';
    }
    html+=data.title;
    html+='</div>';
    html+='<p>'+data.content+'</p>';
    if(data.image){
        html+='<p><img src="'+data.image+'" width="40%" /></p>';
    }
    html+='</div>';
    html+='</div>';
    $('#match-result').prepend(html);
 }
