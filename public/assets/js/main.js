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
        tags: "true",
        placeholder: "Select an option",
    });


    /*----------------------------------------------
        Light & Dark Mode
    ----------------------------------------------*/
    function updateLogoOnTheme() {
        // White logo Dark logo path Change
        var changeLogos = document.querySelectorAll(".changeLogo");
        var whiteLogoPath = document.head
            .querySelector("meta[white-logo]")
        var lightLogoPath = document.head
            .querySelector("meta[logo]")

        var imagePath =
            localStorage.theme === "dark" ? whiteLogoPath : lightLogoPath;
        changeLogos.forEach(function (changeLogo) {
            changeLogo.src = imagePath;
        });
    }
    updateLogoOnTheme();
    // Button icon change
    const themeModeAction = () => {
        if (localStorage.theme === "dark") {
            $(".change-theme-mode i").attr(
                "class",
                "ri-moon-line",
                "ri-sun-line"
            );
        } else {
            $(".change-theme-mode i").attr(
                "class",
                "ri-sun-line",
                "ri-moon-line"
            );
        }
    };
    themeModeAction();
    //Toggle Buttons
    const ToggleThemes = document.getElementsByClassName("ToggleThemeButton");
    for (const ToggleTheme of ToggleThemes) {
        ToggleTheme.addEventListener("click", () => {
            const theme = localStorage.theme === "dark" ? "light" : "dark";
            setTheme(theme);
            themeModeAction();
            updateLogoOnTheme();
        });
    }



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
    $('.single-date-picker').each(function () {
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
    var $input = $('input[name=tag]').tagify({
        whitelist: [
            { "id": 1, "value": "some string" }
        ]
    })

    var input = document.querySelector('input[name="input-custom-dropdown"]'),
        // init Tagify script on the above inputs
        tagify = new Tagify(input, {
            whitelist: ["A# .NET", "A# (Axiom)", "A-0 System", "A+", "A++", "ABAP", "ABC", "ABC ALGOL", "ABSET", "ABSYS", "ACC", "Accent", "Ace DASL", "ACL2", "Avicsoft", "ACT-III", "Action!", "ActionScript", "Ada", "Adenine", "Agda", "Agilent VEE", "Agora", "AIMMS", "Alef", "ALF", "ALGOL 58", "ALGOL 60", "ALGOL 68", "ALGOL W", "Alice", "Alma-0", "AmbientTalk", "Amiga E", "AMOS", "AMPL", "Apex (Salesforce.com)", "APL", "AppleScript", "Arc", "ARexx", "Argus", "AspectJ", "Assembly language", "ATS", "Ateji PX", "AutoHotkey", "Autocoder", "AutoIt", "AutoLISP / Visual LISP", "Averest", "AWK", "Axum", "Active Server Pages", "ASP.NET", "B", "Babbage", "Bash", "BASIC", "bc", "BCPL", "BeanShell", "Batch (Windows/Dos)", "Bertrand", "BETA", "Bigwig", "Bistro", "BitC", "BLISS", "Blockly", "BlooP", "Blue", "Boo", "Boomerang", "Bourne shell (including bash and ksh)", "BREW", "BPEL", "B", "C--", "C++ – ISO/IEC 14882", "C# – ISO/IEC 23270", "C/AL", "Caché ObjectScript", "C Shell", "Caml", "Cayenne", "CDuce", "Cecil", "Cesil", "Céu", "Ceylon", "CFEngine", "CFML", "Cg", "Ch", "Chapel", "Charity", "Charm", "Chef", "CHILL", "CHIP-8", "chomski", "ChucK", "CICS", "Cilk", "Citrine (programming language)", "CL (IBM)", "Claire", "Clarion", "Clean", "Clipper", "CLIPS", "CLIST", "Clojure", "CLU", "CMS-2", "COBOL – ISO/IEC 1989", "CobolScript – COBOL Scripting language", "Cobra", "CODE", "CoffeeScript", "ColdFusion", "COMAL", "Combined Programming Language (CPL)", "COMIT", "Common Intermediate Language (CIL)", "Common Lisp (also known as CL)", "COMPASS", "Component Pascal", "Constraint Handling Rules (CHR)", "COMTRAN", "Converge", "Cool", "Coq", "Coral 66", "Corn", "CorVision", "COWSEL", "CPL", "CPL", "Cryptol", "csh", "Csound", "CSP", "CUDA", "Curl", "Curry", "Cybil", "Cyclone", "Cython", "Java", "Javascript", "M2001", "M4", "M#", "Machine code", "MAD (Michigan Algorithm Decoder)", "MAD/I", "Magik", "Magma", "make", "Maple", "MAPPER now part of BIS", "MARK-IV now VISION:BUILDER", "Mary", "MASM Microsoft Assembly x86", "MATH-MATIC", "Mathematica", "MATLAB", "Maxima (see also Macsyma)", "Max (Max Msp – Graphical Programming Environment)", "Maya (MEL)", "MDL", "Mercury", "Mesa", "Metafont", "Microcode", "MicroScript", "MIIS", "Milk (programming language)", "MIMIC", "Mirah", "Miranda", "MIVA Script", "ML", "Model 204", "Modelica", "Modula", "Modula-2", "Modula-3", "Mohol", "MOO", "Mortran", "Mouse", "MPD", "Mathcad", "MSIL – deprecated name for CIL", "MSL", "MUMPS", "Mystic Programming L"],
            maxTags: 10,
            dropdown: {
                maxItems: 20,           // <- mixumum allowed rendered suggestions
                classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
                enabled: 0,             // <- show suggestions on focus
                closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
            }
        })

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


    /*-----------------------------------
      Home page variant selected
    -----------------------------------*/
    $(document).ready(function () {
        $('input[name="test"]').change(function () {
            const selectedId = $(this).attr('id');
            $('.section-tittle span').text(selectedId);
        });
    });

    /*-----------------------------------
     Append Menu
    -----------------------------------*/
    $(document).ready(function () {
        $('.add-button-menu, .add-button-services').click(function () {
            const newRow = $(this).closest('.append-row').clone();
            newRow.find('input').val('');
            newRow.find('.add-button-menu, .add-button-services').remove();
            newRow.find('.d-flex').append(`
                <button class="btn-danger-fill min-w-100" type="button">
                    <span class="d-flex align-items-center gap-6">
                        <i class="ri-close-line"></i>
                        <span>Close</span>
                    </span>
                </button>
            `);
            if ($(this).hasClass('add-button-menu')) {
                $('.menu-sections').append(newRow);
            } else {
                $('.services-section').append(newRow);
            }
        });
        $('.menu-sections, .services-section').on('click', '.btn-danger-fill', function () {
            $(this).closest('.append-row').remove();
        });
    });



})(jQuery);



