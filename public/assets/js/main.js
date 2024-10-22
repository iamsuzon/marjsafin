/*----------------------------------------------
    Index Of Script
------------------------------------------------

    @version         : 1.0
    @Template Name   : @admin starter
    @Template author : @company

    :: Sidebar Toggle                   ::  mCustomScrollbar Scrolling
    :: Product items seleted            :: Date Ranger
    :: Chat-box message Active single   :: File Upload btn style
    :: Select 2 Single / Multiple       :: Single DatePicker
    :: Light & Dark Mode                :: Password Show Hide
    :: Tagify                           :: Summernote

------------------------------------------------
    / Script
------------------------------------------------*/

(function ($) {
    "use strict";

    // Loader
    $(window).on('load', function () {
        setTimeout(function () { // allowing 3 secs to fade out loader
            $('.page-loader').fadeOut('slow');
        }, 300);
    });

    /*-----------------------------------
      Sidebar Toggle
    -----------------------------------*/
    var scrollTop;
    $(".half-expand-toggle").on("click", function () {
        scrollTop = $(".sidebar-menu").offset().top;
        $("#layout-wrapper").toggleClass("half-expand");
    });
    $(".close-toggle").on("click", function () {
        $("#layout-wrapper").toggleClass("sidebar-expand");
    });

    /*-----------------------------------
      Product items seleted
    -----------------------------------*/
    $('.single-product').click(function () {
        $(this).toggleClass('selected');
    })

    /*-----------------------------------
        Chat-box message Active single
    -----------------------------------*/
    $('.single-chat').click(function () {
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
    })

    /*----------------------------------------------
      Select 2 Single
    ----------------------------------------------*/
    $(".select2").select2({
        placeholder: "Choose one",
        width: "100%",
    });

    /*----------------------------------------------
      Select 2 Single [Modal] // Modal select2 issue
    ----------------------------------------------*/
    $(".select2-modal").select2({
        placeholder: "Choose one",
        width: "100%",
        dropdownParent: $(".modal")  // Modal select2 issue
    });

    /*----------------------------------------------
      Select 2 Multiple
    ----------------------------------------------*/
    $(".multiple-select").select2({
        multiple: true,
        width: "100%",
        placeholder: "Choose one",
        tags: "true",
    });


    /*----------------------------------------------
      Light & Dark Mode
    ----------------------------------------------*/
    var dark = false;
    const lightDark = () => {
        if (localStorage.getItem("isDarkMode") == 'true') {
            $('body').addClass("dark-theme");
            $('.change-mode i').attr("class", "ri-moon-clear-fill", "ri-moon-line")
            dark = true;
        } else {
            $('.change-mode i').attr("class", "ri-moon-line", "ri-moon-clear-fill")
            $('body').removeClass("dark-theme");
            $('body').addClass("default-theme");
            dark = false;
        }
    }
    $(".change-mode").on("click", function () {
        dark = dark ? false : true;
        localStorage.setItem("isDarkMode", dark);
        lightDark();
    });
    $(document).ready(() => {
        lightDark();
    })

    /*----------------------------------------------
      Scrolling
    ----------------------------------------------*/
    $('.scroll-active').each((index, el) => new SimpleBar(el));


    /*-----------------------------------
      Date Ranger
    -----------------------------------*/
    $('.date-range-picker').length > 0 &&

    $(function () {

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('.date-range-picker span, .daterange2 span').html(
                start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY')
            );
        }

        $('.date-range-picker, .daterange2').daterangepicker(
            {
                startDate: start,
                endDate: end,
                ranges: {

                    singleDatePicker: true,
                    Today: [moment(), moment()],
                    Yesterday: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [
                        moment().subtract(1, 'month').startOf('month'),
                        moment().subtract(1, 'month').endOf('month'),
                    ],
                },
            },
            cb
        );
        cb(start, end);
    });

    /*-----------------------------------
      File Upload btn style
    -----------------------------------*/
    var fileInp1 = document.getElementById("fileBrouse1");
    var fileInp2 = document.getElementById("fileBrouse2");
    var fileInp3 = document.getElementById("fileBrouse3");
    var fileInp4 = document.getElementById("fileBrouse4");
    var fileInp5 = document.getElementById("fileBrouse5");

    if (fileInp1) {
        fileInp1.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInp = event.srcElement;
            var fileName = fileInp.files[0].name;
            document.getElementById("placeholder1").placeholder = fileName;
        }
    }
    if (fileInp2) {
        fileInp2.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInp = event.srcElement;
            var fileName = fileInp.files[0].name;
            document.getElementById("placeholder2").placeholder = fileName;
        }
    }
    if (fileInp3) {
        fileInp3.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInp = event.srcElement;
            var fileName = fileInp.files[0].name;
            document.getElementById("placeholder3").placeholder = fileName;
        }
    }
    if (fileInp4) {
        fileInp4.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInp = event.srcElement;
            var fileName = fileInp.files[0].name;
            document.getElementById("placeholder4").placeholder = fileName;
        }
    }
    if (fileInp5) {
        fileInp4.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInp = event.srcElement;
            var fileName = fileInp.files[0].name;
            document.getElementById("placeholder5").placeholder = fileName;
        }
    }

    /*-----------------------------------
      Product Counter Cart Table
    -----------------------------------*/
    var incrementPlus;
    var incrementMinus;
    var buttonPlus = $(".count-plus");
    var buttonMinus = $(".count-minus");

    var incrementPlus = buttonPlus.click(function () {
        var $n = $(this)
            .parent(".button-container")
            .parent(".productCount")
            .find(".qty");

        $n.val(Number($n.val()) + 1);
    });
    var incrementMinus = buttonMinus.click(function () {
        var $n = $(this)
            .parent(".button-container")
            .parent(".productCount")
            .find(".qty");

        var amount = Number($n.val());
        if (amount > 0) {
            $n.val(amount - 1);
        }
    });

    /*-----------------------------------
      Single DatePicker
    -----------------------------------*/
    $('.single-date-picker').each(function() {
        $(this).daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
        });
        $(this).val('');
        $(this).attr("autocomplete", "off");
    });

    /*-----------------------------------
      Tagify
    -----------------------------------*/
    // Tagify activation codes are in custom.js file


    /*-----------------------------------
      Password Show Hide
    -----------------------------------*/
    $(document).ready(function () {
        $(".toggle-password").click(function () {
            var passwordInput = $($(this).siblings(".password-input"));
            var icon = $(this);
            if (passwordInput.attr("type") == "password") {
                passwordInput.attr("type", "text");
                icon.removeClass("ri-eye-line").addClass("ri-eye-off-line");
            } else {
                passwordInput.attr("type", "password");
                icon.removeClass("ri-eye-off-line").addClass("ri-eye-line");
            }
        });
    })

    /*-----------------------------------
      Summernote
    -----------------------------------*/
    var summernoteOptions = {
        blockquoteBreakingLevel: 2,
        disableDragAndDrop: true,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript', 'fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['ltr', 'rtl']],
            ['insert', ['link', 'picture', 'video', 'hr']],
            ['table', ['table']],
            ['air', ['undo', 'redo']],
            ['view', ['codeview']]
        ]
    };
    $(document).ready(function () {
        $('.summernote').length > 0 && $('.summernote').summernote(summernoteOptions);
    });


    $('#myModal1').on('shown.bs.modal', function (e) {
        $(document).off('focusin.modal');
    })
})(jQuery);


