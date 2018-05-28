/**
 * Created by Administrator on 2018/5/25/025.
 */
$(function(){
   $('#disscus-box').keydown(function(event){
      if (event.keyCode == 13){
          var text=$(this).val();
          var url="http://swoole.com/index/chart/index";
          var data={'content':text,'game_id':1};
          var that=$(this);
          $.ajax({
             'data':data,
              'type':'POST',
              'dataType':'json',
              'url':url,
              'success':function(data){
                 that.val("");
              }
          });
      }
   });
});