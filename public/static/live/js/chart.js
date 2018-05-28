/**
 * Created by Administrator on 2018/5/24/024.
 */
var wsUrl="ws://swoole.com:9502";
var websocket=new WebSocket(wsUrl);
websocket.onopen=function(evt){
}
websocket.onmessage=function(evt){
    console.log(evt.data);
    push(JSON.parse(evt.data))
}
websocket.onclose=function (evt) {
    console.log('close success');
}
websocket.onerror=function(evt){
    console.log('some errors'+evt.data);
}
function push(data) {
    var html='<div class="comment">';
    html+='<span>'+data.user+' </span>';
    html+='<span>'+data.content+'</span>';
    html+='</div>';
    console.log(html);
    $('#comment-first').remove();
    $('#comments').append(html);
}