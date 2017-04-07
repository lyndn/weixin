$(function(){
		   
var ie6 = /msie 6/i.test(navigator.userAgent),
dv = $('#end'),st;
$('#end').attr("otop", dv.offset().top); 

$(window).scroll(function() {
	st = Math.max(document.body.scrollTop || document.documentElement.scrollTop);
});

	var offset = $("#end").offset();
	
    $("#backToTop").on("click", function(){
        var _this = $(this);
        $('html,body').animate({ scrollTop: 0 }, 500 ,function(){
          _this.hide();
        });
        return false;
    });

    
    $(window).scroll(function(){
        var htmlTop = $(document).scrollTop();
        if( htmlTop > 10){
            $("#backToTop").show(); 
        }else{
            $("#backToTop").hide();
        }
    });	   
		   
	
	$("body").on("mouseenter" ,"#memberInfo" ,function(){
        $(this).find('.login_info').addClass('login_info_select');
    });

    $("body").on("mouseleave" ,"#memberInfo" ,function(){
        $(this).find('.login_info').removeClass('login_info_select');
    });
	
    $('.lazy').on("click", function(){
		$(".modal-scrollable").show();
		$(".modal-backdrop").show();
		var tid = $(this).attr("data-tid");
		
		if(isnews==0){
			var data = {}; 
			data.id =tid;
			data.action = "add";
			jQuery.ajax({ 
					type:"POST", 
						url: "api/newsshow.php", 
						data:data, 
						dataType: "json", 
						contentType: "application/x-www-form-urlencoded; charset=utf-8", 
						beforeSend: function(XMLHttpRequest){ 
						}, success:function(data) {
						
							$(".rich_media_title").html(data.title)
							$(".rich_media_content").html(data.content)
							$(".rich_media_meta_date").html(data.date)
							
							
						}, error:function(){ 
						} 
				});
		
		}else{
			
			top.location.href='news_show.php?id='+tid;
		}
		
		
	})
	
	
	
	
	
	$(".close").on("click", function(){
		$(".modal-scrollable").hide();
		$(".modal-backdrop").hide();
		return false;
	});
	$(".qqclose").on("click", function(){
		$("#BJ").hide();
		$(".login").hide();
		return false;
	});
	$("#modal-footer").on("click", function(){
		$(".modal-scrollable").hide();
		$(".modal-backdrop").hide();
		return false;
	});

	$("#wList").masonry({
		itemSelector: ".designItem",
		singleMode: true,
		isAnimated: false,
		resizeable: true
	});

	$(".lazy").lazyload({
		effect: "fadeIn"
	});


	$(".header-right").unbind('click').on("click", function(event){

	//$(".header-right").on("click", function(){
		$("#material-synthesizer-pop").slideUp(500);
		return false;
	});

$(".material-synthesizer-btn").unbind('click').on("click", function(event){
	//$(".material-synthesizer-btn").on("click", function(){
		$("#material-synthesizer-pop").slideToggle();
		return false;
	});


//删除文章
$(".mbtrash").on("click", function () {
    var tid = $(this).attr("data-tid");
    var data = {};
    data.ids =tid;
    data.action = "del";
    jQuery.ajax({
        type:"POST",
        url: "/material/add/weichatmsg/fetchedtor",
        data:data,
        dataType: "html",
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        beforeSend: function(XMLHttpRequest){
        }, success:function(data) {
            var t = JSON.parse(data);
            alert(t.message);
            top.location.href='/material/index/weichatmsg';
        }, error:function(){
        }
    });
	return false;
})

//编辑文章
$(".mbbianji").on("click", function(){
	var tid = $(this).attr("data-tid");
	var title = $(this).attr("data-title");
	var fpic = $(this).attr("data-fpic");
	var ndata = '{"res_article_id":"' + tid + '","res_article_title":"' + title + '","res_thumb_image":"' + fpic + '"}'
	localData.set("material_pop", "[" + ndata + "]");
				var data = {}; 
				data.ids =tid;
				data.action = "add";
				jQuery.ajax({ 
					type:"POST", 
					url: "/material/add/weichatmsg/fetchedtor",
					data:data, 
					dataType: "html", 
					contentType: "application/x-www-form-urlencoded; charset=utf-8", 
					beforeSend: function(XMLHttpRequest){ 
					}, success:function(data) {
                        var t = JSON.parse(data)[0].tid;
                        localData.remove("materialData");
                        localData.remove("mdataId");
                        localData.set("mdataId",t);
                        localData.set("materialData",data,'html');
                        $("#toast-container").show();
                        $(".toast").removeClass("toast-error");
                        $(".toast").addClass("toast-success");
                        $("#toast-container .toast-title").html("SUCESS");
                        $("#toast-container .toast-message").html("素材合并中请等待....");
                        setTimeout(function(){$("#toast-container").hide();},1500);
                        setTimeout("top.location.href='/material/add/weichatmsg'",1500);
					}, error:function(){ 
					} 
				});

	return false;
});

    $(".js-edit").click(function() {
        var tid = $(this).attr("data-tid");


        $.get("api/my_media.php?action=list",{"tid":tid,randstr: Date.parse(new Date())},function(data){

            localData.remove("materialData");
            localData.set("materialData",data);
        },'html')


        localData.remove("mdataId");
        localData.set("mdataId", tid);
        $("#toast-container").show();
        $(".toast").removeClass("toast-error");
        $(".toast").addClass("toast-success");
        $("#toast-container .toast-title").html("SUCESS");
        $("#toast-container .toast-message").html("素材合并中请等待....");
        setTimeout(function(){$("#toast-container").hide();},1500);
        setTimeout("top.location.href='editor.php'",1500);


    });





function created() {
	var oldData = localData.get("material_pop");
	data = JSON.parse(oldData);
	if (data instanceof Array == false) {
		data = [];
	}
	var temlen = data.length;
	$("#end").html(temlen)
	$("#nus").html(temlen)
	var template = '';
	var result='';
	$.each(data, function(index, value) {
		template += "<li><div class='del' data-tid="+value.res_article_id+"></div><div class='img' style=\"background-image: url(" + value.res_thumb_image + ");\"></div><p>" + value.res_article_title + "</p></li>"
		//result +=value.res_article_id+","
	});
	$("#ul-box-list").html(template)
	
				
	
	$(".del").on("click", function(){
		var tid = $(this).attr("data-tid");
		var oldData = localData.get("material_pop");
		data = JSON.parse(oldData);
		if (data instanceof Array == false) {
			data = [];
		}
		
		var result='';
		$.each(data, function(index, value) {
			if (value.res_article_id === tid) {
				}else{
				result += '{"res_article_id":"' + value.res_article_id + '","res_article_title":"' + value.res_article_title + '","res_thumb_image":"' + value.res_thumb_image + '"},'
				}
		});
		
		
	  var lastIndex = result.lastIndexOf(',');
      if (lastIndex > -1) {
          new_result = result.substring(0, lastIndex) + result.substring(lastIndex + 1, result.length);
      }else{
	   new_result =result;
	  }
	 
	 
	localData.set("material_pop", "[" + new_result + "]");
	created();
	return false;
});
	
}

created();

$(".btn-default").on("click", function(){
	localData.remove("materialData");
	localData.remove("material_pop");
	localData.remove("mdataId");
	created()
	
		$("#toast-container").show();
		$(".toast").removeClass("toast-error");
		$(".toast").addClass("toast-success");
		$("#toast-container .toast-title").html("错误:");
		$("#toast-container .toast-message").html("成功清除。。");
		setTimeout(function(){$("#toast-container").hide();},3000);
	return false;
});

//合成为图文
    $(".btn-primary").on("click", function () {

        var oldData = localData.get("material_pop");
        data = JSON.parse(oldData);
        if (data instanceof Array == false) {
            data = [];
        }


        if (data.length <= 0) {

            $("#toast-container").show();
            $(".toast").removeClass("toast-success");
            $(".toast").addClass("toast-error");
            $("#toast-container .toast-title").html("错误:");
            $("#toast-container .toast-message").html("至少选择一个图文素材吧");
            setTimeout(function () {
                $("#toast-container").hide();
            }, 3000);


            return false;
        }
        var result = '';
        $.each(data, function (index, value) {
            result += value.res_article_id + ","
        });


        var lastIndex = result.lastIndexOf(',');
        if (lastIndex > -1) {
            new_result = result.substring(0, lastIndex) + result.substring(lastIndex + 1, result.length);
        } else {
            new_result = result;
        }


        var data = {};
        data.ids = new_result;
        data.action = "compound";
        jQuery.ajax({
            type: "POST",
            url: "/material/add/weichatmsg/fetchedtor",
            data: data,
            dataType: "html",
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            beforeSend: function (XMLHttpRequest) {
            }, success: function (data) {
                var t = JSON.parse(data)[0].tid;
                localData.remove("materialData");
                localData.remove("mdataId");
                localData.set("materialData", data, 'html');
                $("#toast-container").show();
                $(".toast").removeClass("toast-error");
                $(".toast").addClass("toast-success");
                $("#toast-container .toast-title").html("SUCESS");
                $("#toast-container .toast-message").html("素材合并中请等待....");
                setTimeout(function () {
                    $("#toast-container").hide();
                }, 1500);
                setTimeout("top.location.href='/material/add/weichatmsg'", 1500);
            }, error: function () {
            }
        });
        return false;
    });


$(".del").on("click", function(){
		var tid = $(this).attr("data-tid");
		var oldData = localData.get("material_pop");
		data = JSON.parse(oldData);
		if (data instanceof Array == false) {
			data = [];
		}
		var result='';
		$.each(data, function(index, value) {
			if (value.res_article_id === tid) {
				}else{
				result += '{"res_article_id":"' + value.res_article_id + '","res_article_title":"' + value.res_article_title + '","res_thumb_image":"' + value.res_thumb_image + '"},'
				}
		});
	  var lastIndex = result.lastIndexOf(',');
      
      if (lastIndex > -1) {
          new_result = result.substring(0, lastIndex) + result.substring(lastIndex + 1, result.length);
      }else{
	   new_result =result;
	  }
	
	localData.set("material_pop", "[" + new_result + "]");
	created();
	return false;
});

$(".fa-heart-o").unbind('click').on("click", function(event){
//$(".fa-heart-o").on("click", function(){
				var data = {}; 
				data.tid = $(this).attr("data-tid");
				data.action = "add";
				jQuery.ajax({ 
					type:"POST", 
					url: "api/addheart.php", 
					data:data, 
					dataType: "json", 
					contentType: "application/x-www-form-urlencoded; charset=utf-8", 
					beforeSend: function(XMLHttpRequest){ 
					}, success:function(data) {
						if(data.uid==0){
							$(".login,#BJ").show();
							var url="login.php?a=qq";
							$(".login_con_iframe").attr("src",url)
							$(".login_tit_span").html("登录素材助手")
							
							 $("#BJ").css({
								height: $(document).height()
							  });
						   $(".login").css({
							 left: ($(window).width() - 537) / 2 + "px",
							 top: ($(window).height() - 370) / 2 + "px"
						   });	
	
						}else{
						
							if(data.isok==0){
							   $("#toast-container").show();
								$(".toast").removeClass("toast-success");
								$(".toast").addClass("toast-error");

								$("#toast-container .toast-title").html("SUCESS");
								$("#toast-container .toast-message").html("素材已经收藏过了，不可以重复收藏");
								setTimeout(function(){$("#toast-container").hide();},1000);
							}else{
							
								$(this).parent().css("background","#000000");
								$("#toast-container").show();
								$(".toast").removeClass("toast-error");
								$(".toast").addClass("toast-success");
								$("#toast-container .toast-title").html("SUCESS");
								$("#toast-container .toast-message").html("收藏素材成功");
								setTimeout(function(){$("#toast-container").hide();},1000);
							}	
						}
						
					}, error:function(){ 
					} 
				});
	
});

//加入素材合成器
$(".add-material").unbind('click').on("click", function(event){
	var addcar = $(this);
	var tid = $(this).attr("data-tid");
	alert(tid);
	var title = $(this).attr("data-title");
	var fpic = $(this).attr("data-fpic");
	var ndata = '{"res_article_id":"' + tid + '","res_article_title":"' + title + '","res_thumb_image":"' + fpic + '"}'
	var oldData = localData.get("material_pop");
	data = JSON.parse(oldData);
	if (data instanceof Array == false) {
		data = [];
	}
	
	
	if (data.length >= 8) {
		$("#toast-container").show();
		$(".toast").removeClass("toast-success");
		$(".toast").addClass("toast-error");
		$("#toast-container .toast-title").html("错误:");
		$("#toast-container .toast-message").html("素材合成器最多只能添加8篇");
		setTimeout(function(){$("#toast-container").hide();},3000);
		return false;
	}
	
	
	var result = '';
	$.each(data, function(index, value) {
		if (value.res_article_title === title) {
			$("#toast-container").show();
			$(".toast").removeClass("toast-success");
			$(".toast").addClass("toast-error");
			$("#toast-container .toast-title").html("错误:");
			$("#toast-container .toast-message").html("这篇文章已经在素材合成器里了");
			setTimeout(function(){$("#toast-container").hide();},3000);
			return false;
		}
		
		
		result += '{"res_article_id":"' + value.res_article_id + '","res_article_title":"' + value.res_article_title + '","res_thumb_image":"' + value.res_thumb_image + '"},'
	});

	new_result = result + ndata;
	localData.set("material_pop", "[" + new_result + "]");
	created()
	
	var img = addcar.parent().parent().find('img').attr('src');
	var flyer = $('<img class="u-flyer" src="' + fpic + '">');
	flyer.fly({
		start: {
			left: event.clientX,
			top: event.clientY
		},
		end: {
			left: offset.left,
			top: offset.top - parseInt($('#end').attr('otop')) + 120,
			width: 0,
			height: 0
		},
		onEnd: function() {
			//$(".add-material").unbind();        //解绑点击事件
			$("#material-synthesizer-pop").show();
			this.destory();
		}
	});
});

$(".tran").on("click", function(){
        $(".login,#BJ").show();
		var url="login.php?a="+$(this).attr("rel");
		$(".login_con_iframe").attr("src",url);
         $("#BJ").css({
            height: $(document).height()
          });
           $(".login").css({
             left: ($(window).width() - 537) / 2 + "px",
               top: ($(window).height() - 370) / 2 + "px"
           });	
        return false;
    });


	
	$(".my_out").on("click", function(){
				var data = {}; 
				data.action = "loginout";
				jQuery.ajax({ 
					type:"POST", 
					url: "api/members_inc.php", 
					data:data, 
					dataType: "html", 
					contentType: "application/x-www-form-urlencoded; charset=utf-8", 
					beforeSend: function(XMLHttpRequest){ 
					}, success:function(data) {
					
						localData.remove("mdataId");
						localData.remove("materialData");
						localData.remove("material_pop");
						top.location.href='index.php';
						
					}, error:function(){ 
					} 
				});
	
	 return false;
    });
	
		

	
})	