function make_channel_args(value,pin){
	var args = $('#channel_args').val().split(",");
	args[pin] = $.trim(value);
	$('#channel_args').val(args.join());
}

$(document).ready(function(){
$(".slideToggler").click(function(){
    $(".slide-toggle").slideToggle();
});
  
$(".new_schema").click(function(){
	var len = $(".table-hover").children("tbody").children("tr").length;
	if(len < 5) return true;
	$('#message-box').modal('toggle');
//	alert("您已达到可建立计划的最大值");
	return false;
});
  
$(".test_schema").click(function(){
	var numError = $('form .onError').length;
	if(!numError){
		$(this).button('loading');
		$(this).html('努力加载中');
		var keywords = $(":input[name='keywords']").val().replace(" ","^^");
		htmlobj=$.ajax({url:"/schema/test/?channel="+$(":input[name='channel']").val()+"&url="+$(":input[name='url']").val()+"&keywords="+keywords,async:false});
		if(htmlobj.responseText){
			$("#myModalLabel").html('抓取成功');
			$("#myModalBody").html(htmlobj.responseText);
		}else{
			$("#myModalLabel").html('抓取失败');
			$("#myModalBody").html('建议尝试减少关键词数，或者调换关键词顺序');
		}
		$('#message-box').modal('toggle');
		$(this).button('complete');
		$(this).html('测试');
	}
});

$('.tooltip-container').tooltip({
	selector : "a[data-toggle=tooltip]"
})
});