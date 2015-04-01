$(function(){
		/*
		*思路大概是先为每一个required添加必填的标记，用each()方法来实现。
		*在each()方法中先是创建一个元素。然后通过append()方法将创建的元素加入到父元素后面。
		*required 为自身必填class，id可不填；
		*required-leader为领导必填class，id可不填，触发后会检测required-follower是否已填，自身没有提示；
		*required-follower为跟随必填，id必填，只有当required-leader为空时才是必填；
		//如果是必填的，则加红星标识.
		$("form :input.required").each(function(){
			var $required = $("<strong class='high'> *</strong>"); //创建元素
			$(this).parent().append($required); //然后将它追加到文档中
		});
		*/
		//文本框失去焦点后
		$('form :input').blur(function(){
			var $parent = $(this).parent();
			var $grandparent = $parent.parent();
			var $greatgrandparent = $grandparent.parent();
			$parent.find(".formtips").remove();
			$grandparent.removeClass('error');
			$grandparent.find(".formtips").remove();
			$greatgrandparent.removeClass('error');

			//验证用户名
			if( $(this).is('#username') ){
				if( this.value=="" || this.value.length < 6 ){
					var errorMsg = '请输入至少6位的用户名.';
					$parent.append('<span class="formtips help-inline onError">'+errorMsg+'</span>');
					$grandparent.addClass('error');
				}else{
					var okMsg = '输入正确.';
					$parent.append('<span class="formtips help-inline onSuccess">'+okMsg+'</span>');
				}
			}else if( $(this).is('#email') ){//验证邮件
				if( this.value=="" || ( this.value!="" && !/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value) ) ){
					 var errorMsg = '请填写邮箱.';
					 $parent.append('<span class="formtips help-inline onError">'+errorMsg+'</span>');
					 $grandparent.addClass('error');
				}
			}else if( $(this).is('#email-append') ){//验证邮件
				if( this.value=="" || ( this.value!="" && !/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value) ) ){
					 var errorMsg = '请填写邮箱.';
					 $grandparent.append('<span class="formtips help-inline onError">'+errorMsg+'</span>');
					 $greatgrandparent.addClass('error');
				}
			}else if($(this).is('#repeat-password') ){//密码
				if( this.value=="" || this.value != $('input#password').val() ){
					var errorMsg = '密码不相同.';
					$parent.append('<span class="formtips help-inline onError">'+errorMsg+'</span>');
					$grandparent.addClass('error');
				}
			}else if($(this).is('#url') ){//url必须包含http://
				if( this.value=="" || ( this.value!="" && !/((\w+:\/\/)[-a-zA-Z0-9:@;?&=\/%\+\.\*!'\(\),\$_\{\}\^~\[\]`#|]+\.[-a-zA-Z0-9:@;?&=\/%\+\.\*!'\(\),\$_\{\}\^~\[\]`#|]+)/.test(this.value) ) ){
					var errorMsg = '请填写，并以http://开头';
					$parent.append('<span class="formtips help-inline onError">'+errorMsg+'</span>');
					$grandparent.addClass('error');
				}
			}else if( $(this).is("#text") || $(this).is('.required')){
				if( this.value==""){
					var errorMsg = '请填写.';
					$parent.append('<span class="formtips help-inline onError">'+errorMsg+'</span>');
					$grandparent.addClass('error');
				}
			}
			
			//领导
			if( $(this).is('.required-leader') && this.value==""){
				$("form :input.required-follower").trigger('blur');
			}
		}).keyup(function(){
		  $(this).triggerHandler("blur");
		}).focus(function(){
			$(this).triggerHandler("blur");
		});//end blur

		
		//提交，最终验证。
		$('.form-submit').click(function(){
			$("form :input.required").trigger('blur');
			$("form :input.required-leader").trigger('blur');
			var numError = $('form .onError').length;
			if(numError){
				return false;
			}
		});

		//重置
		$('#res').click(function(){
			$(".formtips").remove();
			$(".error").removeClass('error');
		});
})