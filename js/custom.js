/* Ici c'est le code WOKE */

// Afficher une dropdownlist en survolant avec la souris
if(window.innerWidth >=768){
  $("li.dropdown").mouseenter( handlerIn ).mouseleave( handlerOut );
}

function handlerIn(){
    $(this).addClass("show")
    $(this).children("div.dropdown-menu").addClass("show");
    $(this).children("a.nav-link.dropdown-toggle").attr("aria-expanded", "true");
}

function handlerOut(){
    $(this).removeClass("show");
    $(this).children("div.dropdown-menu").removeClass("show");
    $(this).children("a.nav-link.dropdown-toggle").attr("aria-expanded", "false");
}

$(document).ready(function(){
    
$(document).ready(function(){
    $('.your-class').slick({
        dots: true,
        infinite: true,
        autoplay: true,
        slidesToShow: 1,
        slidesToScroll:1,
        responsive: [
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true
              }
            },
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
          ]
    });
  });
});