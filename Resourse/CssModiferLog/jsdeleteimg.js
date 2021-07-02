$('document').ready(function() {
    $(".bloc-text-image").mouseenter(function() {
    $(this).find('.description').stop().fadeIn('slow');
  });
    $(".bloc-text-image").mouseleave(function() {
    $(this).find('.description').stop().fadeOut('slow');
  });
  
$('.bloc-text-image').each(function(i){
      $('<span class="delete">delete</span>').appendTo($(this));
  if(i % 3 == 0){
    $(this).css({
      'clear': 'both'
    });
  }
});
  $(".bloc-text-image .delete").click(function() {
    $(this).closest('.bloc-text-image').fadeOut();
  });
});