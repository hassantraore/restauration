// isotope js
$(window).on("load", function () {
    $(".filters_menu li").click(function () {
        $(".filters_menu li").removeClass("active");
        $(this).addClass("active");

        var data = $(this).attr("data-filter");
        $grid.isotope({
            filter: data,
        });
    });

    var $grid = $(".grid").isotope({
        itemSelector: ".all",
        percentPosition: false,
        masonry: {
            columnWidth: ".all",
        },
    });
});

// nice select
$(document).ready(function () {
    /* $(".dashboard-year").niceSelect(); */
});

// client section owl carousel
$(".client_owl-carousel").owlCarousel({
    loop: true,
    margin: 0,
    dots: false,
    nav: true,
    navText: [],
    autoplay: true,
    autoplayHoverPause: true,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>',
    ],
    responsive: {
        0: {
            items: 1,
        },
        768: {
            items: 2,
        },
        1000: {
            items: 2,
        },
    },
});

$(".owl-carousel").owlCarousel({
    nav: true,
    dots: false,
});
