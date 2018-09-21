$(window).load(function(){
  
  function lazyload(){
     var wt = $(window).scrollTop();
     var wb = wt + $(window).height();
     $(".lazy").each(function(){
        var ot = $(this).offset().top;
        var ob = ot + $(this).height();
        if(!$(this).attr("loaded") && wt<=ob && wb >= ot){
           loadcontent($(this).attr("id"),$(this).attr("src"));
           $(this).attr("loaded",true);
        }
     });
  }

  function loadcontent(id,src) {
      $.ajax({
  		  url: src,
        dataType: "html",
        beforeSend: function() {
              $("#"+id).html('<i class="fa fa-refresh fa-spin"></i>');
        },
  		  success: function(res) {
                $("#"+id).html(res);
  		  },
  		  error:function() {
  			  $("#"+id).html("could not load "+src);
  		  }
  		});
  }

  $(document).ready(function(){
    $(window).scroll(lazyload);
     lazyload();
  });

});
