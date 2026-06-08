$(function(){
  function closeInline(){
    var m = $(".message-icon-name");
    m.css({display:"none"});
  }
  $("body > i").on({
    "click": function(e){
      var m = $(".message-icon-name");
      m.html($(this).prop("outerHTML") + "<br>" + $(this).attr("class"));
      m.css({marginLeft:-(m.width()/2)-20,marginTop:-(m.height()/2)-20});
      m.css({display:"block"});
    }
  });
  $("body").on({
    "click": function(e){
      var m = $(".message-icon-name");
      var temp = $(e.target).attr('class');
      if (temp==="body") {
        m.css({display:"none"});
      }
    }
  });
  setInterval(function(){
    $(".message-icon-name i").unbind().bind({
      "click": function(e){
        closeInline();
      }
    });
  },1000);
});
