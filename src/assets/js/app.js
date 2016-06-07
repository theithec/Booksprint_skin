
$(document).ready( function(){
  $(document).foundation();

/*	// toggle nested menu entries 
  var $subs = $("#toc>ul>li>ul");
  $subs.hide();
  $subs.each(function(index, obj){
    var $sub = $(obj);
    $sub.siblings("a").after('<a class="toggler">+</a>');
    
    console.log($sub.parent());
  });
  $(".toggler").bind("click", function(e){
    $target = $(e.target);
    if ($target.text()==="+"){
      $target.siblings("ul").show();
      $target.text("-");
    }else if ($target.text()==="-"){
      $target.siblings("ul").hide();
      $target.text("+");
    }
  });
  */

});
