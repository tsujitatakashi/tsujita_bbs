$(function(){
  $(".focusName").focus(function(){
    if(this.value == "投稿者名を入力"){
      $(this).val("").css("color","#000000");
    }
  });


  $(".focusName").blur(function(){
    if(this.value == ""){
      $(this).val("投稿者名を入力").css("color","#FF0000");
    }
  });


  $(".focusComment").focus(function(){
    if(this.value == "本文を入力"){
      $(this).val("").css("color","#000000");
    }
  });


  $(".focusComment").blur(function(){
    if(this.value == ""){
      $(this).val("本文を入力").css("color","#FF0000");
    }
  });

  $('.replyToTop').click(function () {
	$('body,html').animate({
		scrollTop: 0
    }, 800);
    return false;
  });


});