					$(".facelist li").on("click", function(){
								
							if($(this).hasClass("li_on")){
								//$('.facelist li').each(function (i){
								$(this).removeClass("li_on");
								//});	
								//$(this).addClass("li_on");
								$("#appid").val(0);
							}else{
								$('.facelist li').each(function (i){
									 $(this).removeClass("li_on");
								});	
								$(this).addClass("li_on");
								$("#appid").val($(this).attr("data-appid"));	
							}
							
							
					});	