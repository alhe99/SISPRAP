$('.BF1').hover(function(){
  $('.BF2').addClass('animacionVer');
})

$('.contenedorBF').mouseleave(function(){
  $('.BF2').removeClass('animacionVer');});

$('.BF1').hover(function(){
  $('.BF3').addClass('animacionVer');
})
$('.contenedorBF').mouseleave(function(){
  $('.BF3').removeClass('animacionVer');});

$('.BF1').hover(function(){
  $('.BF4').addClass('animacionVer');
})
$('.contenedorBF').mouseleave(function(){
  $('.BF4').removeClass('animacionVer');});

$('.BF1').hover(function(){
  $('.BF5').addClass('animacionVer');
})
$('.contenedorBF').mouseleave(function(){
  $('.BF5').removeClass('animacionVer');});



jQuery(document).ready(function() {
  var offset = 220;
  var duration = 500;
  jQuery(window).scroll(function() {
    if (jQuery(this).scrollTop() > offset) {
      jQuery('.back-to-top').fadeIn(duration);
    } else {
      jQuery('.back-to-top').fadeOut(duration);
    }
  });

  jQuery('.back-to-top').click(function(event) {
    event.preventDefault();
    jQuery('html, body').animate({scrollTop: 0}, duration);
    return false;
  })
});


//modal
jQuery(document).ready(function($) {
  // auto timer
  setTimeout(function() {
    $('#lab-slide-bottom-popup').modal('show');
  }, 5000); // optional - automatically opens in xxxx milliseconds

  $(document).ready(function() {
    $('.lab-slide-up').find('a').attr('data-toggle', 'modal');
    $('.lab-slide-up').find('a').attr('data-target', '#lab-slide-bottom-popup');
  });

});

