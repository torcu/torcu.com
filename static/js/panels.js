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

  $(document).on('mouseenter', '.dragbox', function (event) {

    $(this).find('h2').hover(function(){
      $(this).find('.configure').css('visibility', 'visible');
    }, function(){
      $(this).find('.configure').css('visibility', 'hidden');
    })
    .find('.close').click(function(){
      alert("closing "+$(this).attr('id'));
      //$(this).siblings('.dragbox-content').toggle();
    }).end();
    //.find('.configure').css('visibility', 'hidden');
  });

  $('.column').sortable({
    connectWith: '.column',
    handle: 'h2',
    cursor: 'move',
    placeholder: 'placeholder',
    forcePlaceholderSize: true,
    opacity: 0.4,
    stop: function(event, ui){
      $(ui.item).find('h2').click();
      var sortorder='';
      $('.column').each(function(){
        var itemorder=$(this).sortable('toArray');
        var columnId=$(this).attr('id');
        sortorder+=columnId+'='+itemorder.toString()+'&';
      });
      console.log('New Sort Order: '+sortorder);
      /*Pass sortorder variable to server using ajax to save state*/
    }
  }).disableSelection();

  $('#addpanel').click(function() {
    items++;
    $('#column1').append('<div class="dragbox" id="item'+items+'"><h2><span class="configure"><button id="create-user">Create new user</button></span>Handle '+items+'</h2><div id="content'+items+'" class="dragbox-content lazy" src="/panels/getcontent" id="content'+items+'">Content</div></div>');
    lazyload();
  })

  $(document).ready(function(){

    $('.column').sortable({
      connectWith: '.column',
      handle: 'h2',
      cursor: 'move',
      placeholder: 'placeholder',
      forcePlaceholderSize: true,
      opacity: 0.4,
      stop: function(event, ui){
        $(ui.item).find('h2').click();
        var sortorder='';
        $('.column').each(function(){
          var itemorder=$(this).sortable('toArray');
          var columnId=$(this).attr('id');
          sortorder+=columnId+'='+itemorder.toString()+'&';
        });
        console.log('New Sort Order: '+sortorder);
        /*Pass sortorder variable to server using ajax to save state*/
      }
    }).disableSelection();

    $(window).scroll(lazyload);
     lazyload();
  });

});
