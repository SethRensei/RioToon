$(document).ready(() => {
    $("button.btn-close").click(() => {
        $("div.alert-dismissible").fadeOut();
    });

    const nav_bar = $(".nav_bar"),
        header2 = $(".header-2"),
        menu = $("#menu");
    $("#menu").click(function () {
        $(this).toggleClass("fa-times");
        nav_bar.toggleClass("nav-toggle");
    });
    $(window).on("scroll", function () {
        nav_bar.removeClass("nav-toggle");

        if ($(window).scrollTop() > 30) {
            header2.addClass("sticky");
            menu.addClass("menu");
            $(".back-to-top").fadeIn();
        } else {
            header2.removeClass("sticky");
            menu.removeClass("menu");
            $(".back-to-top").fadeOut();
        }
    });

    $(".back-to-top").click(function () {
        $("html, body").animate({ scrollTop: 0 }, 500);
    });

    /* ADMIN */
    $("#sidebar-open").click(() => {
        $(".navigation").toggleClass("active");
    });
})