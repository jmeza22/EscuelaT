function setTitle() {
    $('#cssmenu ul li a').each(function (i) {
        if ($(this).attr('href') == $('#frame').attr('src')) {
            $(this).addClass("selected-item");
        }
    });

}

jQuery(document).ready(function () {
    function open_menu() {
        $('#cssmenu').removeClass('hide-menu');
        $('#cssmenu').slideDown(300).addClass('show-menu');
    }
    function close_menu() {
        $('#cssmenu').removeClass('show-menu');
        $('#cssmenu').slideUp(300).addClass('hide-menu');
    }


    $(document).on("click", '#cssmenu ul li a', function (e) {
        setTitle();
        e.preventDefault();
    });

});