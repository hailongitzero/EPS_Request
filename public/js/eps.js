$(function () {
    let trangThaiObj = {
        0: "Yêu cầu mới",
        1: "Tiếp nhận",
        2: "Đang xử lý",
        3: "Hoàn Thành",
        4: "Từ chối",
    };
    let trangThaiColorClassObj = {
        0: "label-info",
        1: "label-warning",
        2: "label-magenta",
        3: "label-success",
        4: "label-important",
    };

    let uuTienObj = {
        0: 'Thấp',
        1: 'Trung Bình',
        2: 'Cao'
    };
    let uuTienColorClassObj = {
        0: 'label-info',
        1: 'label-success',
        2: 'label-important',
    };

    let YEU_CAU_MOI = 0;
    let TIEP_NHAN = 1;
    let DANG_XU_LY = 2;
    let HOAN_THANH = 3;
    let TU_CHOI = 4;


    //Handel user layout settings using cookie
    function handleUserLayoutSetting() {
        if (typeof cookie_not_handle_user_settings != 'undefined' && cookie_not_handle_user_settings == true) {
            return;
        }
        //Collapsed sidebar
        if ($.cookie('sidebar-collapsed') == 'true') {
            $('#sidebar').addClass('sidebar-collapsed');
        }

        //Fixed sidebar
        if ($.cookie('sidebar-fixed') == 'true') {
            $('#sidebar').addClass('sidebar-fixed');
        }

        //Fixed navbar
        if ($.cookie('navbar-fixed') == 'true') {
            $('#navbar').addClass('navbar-fixed');
        }

        var color_skin = $.cookie('skin-color');
        var color_sidebar = $.cookie('sidebar-color');
        var color_navbar = $.cookie('navbar-color');

        //Skin color
        if (color_skin !== undefined) {
            $('body').addClass('skin-' + color_skin);
        }

        //Sidebar color
        if (color_sidebar !== undefined) {
            $('#main-container').addClass('sidebar-' + color_sidebar);
        }

        //Navbar color
        if (color_navbar !== undefined) {
            $('#navbar').addClass('navbar-' + color_navbar);
        }
    }
    //If you want to handle skin color by server-side code, don't forget to comment next line
    handleUserLayoutSetting();

    //Disable certain links
    $('a[href=#]').click(function (e) {
        e.preventDefault()
    });

    //slimScroll to fixed height tags
    $('.nice-scroll, .slimScroll').slimScroll({ touchScrollStep: 30 });

    //---------------- Tooltip & Popover --------------------//
    $('.show-tooltip').tooltip({ container: 'body', delay: { show: 500 } });
    $('.show-popover').popover();

    //---------------- Syntax Highlighter --------------------//
    window.prettyPrint && prettyPrint();

    //----------------------- Chosen Select ---------------------//
    if (jQuery().chosen) {
        $(".chosen").chosen({
            no_results_text: "Oops, nothing found!",
            width: "100%",
        });

        $(".chosen-with-diselect").chosen({
            allow_single_deselect: true,
            width: "100%"
        });
    }

    //--------------- Password Strength Indicator ----------------//
    if (jQuery().pwstrength) {
        $('input[data-action="pwindicator"]').pwstrength();
    }

    //----------------- Bootstrap Dual Listbox -------------------//
    if (jQuery().bootstrapDualListbox) {
        $('select[data-action="duallistbox"]').bootstrapDualListbox();
    }

    //----------------------- Colorpicker -------------------------//
    if (jQuery().colorpicker) {
        $('.colorpicker-default').colorpicker({
            format: 'hex'
        });
        $('.colorpicker-rgba').colorpicker();
    }

    //----------------------- Time Picker -------------------------//
    if (jQuery().timepicker) {
        $('.timepicker-default').timepicker();
        $('.timepicker-24').timepicker({
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false
        });
    }

    //------------------------ fix CKEDITOR ------------------------//

    // ClassicEditor
    //     .create( document.querySelector( '.ckeditor' ) )
    //     .then( editor => {
    //         console.log( editor );
    //     } )
    //     .catch( error => {
    //         console.error( error );
    //     } );

    //------------------------ Date Picker ------------------------//
    // $.fn.datepicker.defaults.format = "dd/mm/yyyy";
    $('#han_xu_ly_yc, #ngay_xu_ly_yc, #ngay_tao_yc, #ngay_gia_han_div').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });
    var addDate = new Date();
    addDate.setDate(addDate.getDate() + 3);
    $('#han_xu_ly_yc_moi').datepicker({
        startDate: '0d',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    }).datepicker('setDate', addDate);

    $('#tu_ngay_1, #den_ngay_1,#tu_ngay_2, #den_ngay_2').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });
    var currentDate = new Date();
    var firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    $('#tu_ngay_1').datepicker('setDate', firstDayOfMonth);
    $('#den_ngay_1').datepicker('setDate', currentDate);
    $('#tu_ngay_2').datepicker('setDate', firstDayOfMonth);
    $('#den_ngay_2').datepicker('setDate', currentDate);

    //------------------------ Date Range Picker ------------------------//
    if (jQuery().daterangepicker) {
        //Date Range Picker
        $('.date-range').daterangepicker();
    }

    //------------------------ Bootstrap WYSIWYG Editor -----------------//
    if (jQuery().wysihtml5) {
        $('.wysihtml5').wysihtml5();
    }

    //---------------------------- prettyPhoto -------------------------------//
    if (jQuery().prettyPhoto) {
        $(".gallery a[rel^='prettyPhoto']").prettyPhoto({ social_tools: '', hideflash: true });
    }


    //Add animation to notification and messages icon, if they have any new item
    var badge = $('.flaty-nav .dropdown-toggle > .fa-bell + .badge')
    if ($(badge).length > 0 && parseInt($(badge).html()) > 0) {
        $('.flaty-nav .dropdown-toggle > .fa-bell').addClass('anim-swing');
    }
    badge = $('.flaty-nav .dropdown-toggle > .fa-envelope + .badge')
    if ($(badge).length > 0 && parseInt($(badge).html()) > 0) {
        $('.flaty-nav .dropdown-toggle > .fa-envelope').addClass('anim-top-down');
    }

    //---------------- Sidebar -------------------------------//
    //Scrollable fixed sidebar
    var scrollableSidebar = function () {
        if ($('#sidebar.sidebar-fixed').size() == 0) {
            $('#sidebar .nav').css('height', 'auto');
            return;
        }
        if ($('#sidebar.sidebar-fixed.sidebar-collapsed').size() > 0) {
            $('#sidebar .nav').css('height', 'auto');
            return;
        }
        var winHeight = $(window).height() - 90;
        $('#sidebar.sidebar-fixed .nav').slimScroll({ height: winHeight + 'px', position: 'left' });
    }
    scrollableSidebar();
    //Submenu dropdown
    $('#sidebar a.dropdown-toggle').click(function () {
        var submenu = $(this).next('.submenu');
        var arrow = $(this).children('.arrow');
        if (arrow.hasClass('fa-angle-right')) {
            arrow.addClass('anim-turn90');
        } else {
            arrow.addClass('anim-turn-90');
        }
        submenu.slideToggle(400, function () {
            if ($(this).is(":hidden")) {
                arrow.attr('class', 'arrow fa fa-angle-right');
            } else {
                arrow.attr('class', 'arrow fa fa-angle-down');
            }
            arrow.removeClass('anim-turn90').removeClass('anim-turn-90');
        });
    });

    //Collapse button
    $('#sidebar.sidebar-collapsed #sidebar-collapse > i').attr('class', 'fa fa-angle-double-right');
    $('#sidebar-collapse').click(function () {
        $('#sidebar').toggleClass('sidebar-collapsed');
        if ($('#sidebar').hasClass('sidebar-collapsed')) {
            $('#sidebar-collapse > i').attr('class', 'fa fa-angle-double-right');
            $.cookie('sidebar-collapsed', 'true');
            $("#sidebar ul.nav-list").parent('.slimScrollDiv').replaceWith($("#sidebar ul.nav-list"));
        } else {
            $('#sidebar-collapse > i').attr('class', 'fa fa-angle-double-left');
            $.cookie('sidebar-collapsed', 'false');
            scrollableSidebar();
        }
    });

    $('#sidebar').on('show.bs.collapse', function () {
        if ($(this).hasClass('sidebar-collapsed')) {
            $(this).removeClass('sidebar-collapsed');
        }
    });

    $('#sidebar').on('show.bs.collapse', function () {
        $("html, body").animate({ scrollTop: 0 }, 100);
    });

    //Search Form
    $('#sidebar .search-form').click(function () {
        $('#sidebar .search-form input[type="text"]').focus();
    });
    $('#sidebar .nav > li.active > a > .arrow').removeClass('fa-angle-right').addClass('fa-angle-down');

    //---------------- Horizontal Menu -------------------------------//
    if ($('#nav-horizontal')) {
        var horizontalNavHandler = function () {
            var w = $(window).width();
            if (w > 979) {
                $('#nav-horizontal').removeClass('nav-xs');
                $('#nav-horizontal .arrow').removeClass('fa-angle-right').removeClass('fa-angle-down').addClass('fa-caret-down');
            } else {
                $('#nav-horizontal').addClass('nav-xs');
                $('#nav-horizontal .arrow').removeClass('fa-caret-down').addClass('fa-angle-right');
            }
        }
        $(window).resize(function () {
            horizontalNavHandler();
        });
        horizontalNavHandler();
    }

    //Horizontal menu dropdown
    $('#nav-horizontal a.dropdown-toggle').click(function () {
        var submenu = $(this).next('.dropdown-menu');
        var arrow = $(this).children('.arrow');
        if ($('#nav-horizontal.nav-xs').size() > 0) {
            if (arrow.hasClass('fa-angle-right')) {
                arrow.addClass('anim-turn90');
            } else {
                arrow.addClass('anim-turn-90');
            }
        }
        if ($('#nav-horizontal.nav-xs').size() == 0) {
            $('#nav-horizontal > li > .dropdown-menu').not(submenu).slideUp(400);
        }
        submenu.slideToggle(400, function () {
            if ($('#nav-horizontal.nav-xs').size() > 0) {
                if ($(this).is(":hidden")) {
                    arrow.attr('class', 'arrow fa fa-angle-right');
                } else {
                    arrow.attr('class', 'arrow fa fa-angle-down');
                }
                arrow.removeClass('anim-turn90').removeClass('anim-turn-90');
            }
        });
    });

    //------------------ Theme Setting --------------------//
    //Toggle showing theme setting box
    $('#theme-setting > a').click(function () {
        $(this).next().animate({ width: 'toggle' }, 500, function () {
            if ($(this).is(":hidden")) {
                $('#theme-setting > a > i').attr('class', 'fa fa-gears fa-2x');
            } else {
                $('#theme-setting > a > i').attr('class', 'fa fa-times fa-2x');
            }
        });
        $(this).next().css('display', 'inline-block');
    });
    //Change skin and colors
    $('#theme-setting ul.colors a').click(function () {
        var parent_li = $(this).parent().get(0);
        var parent_ul = $(parent_li).parent().get(0);
        var target = $(parent_ul).data('target');
        var prefix = $(parent_ul).data('prefix');
        var color = $(this).attr('class');
        var regx = new RegExp('\\b' + prefix + '.*\\b', 'g');
        $(parent_ul).children('li').removeClass('active');
        $(parent_li).addClass('active');
        //Remove current skin class if it has
        if ($(target).attr('class') != undefined) {
            $(target).attr('class', $(target).attr('class').replace(regx, '').trim());
        }
        $(target).addClass(prefix + color)
        if (target == 'body') {
            var parent_ul_li = $(parent_ul).parent().get(0);
            var next_li = $(parent_ul_li).nextAll('li:lt(2)');
            $(next_li).find('li.active').removeClass('active');
            $(next_li).find('a.' + color).parent().addClass('active');
            //Remove static color class from Navbar & Sidebar
            $('#navbar').attr('class', $('#navbar').attr('class').replace(/\bnavbar-.*\b/g, '').trim());
            $('#main-container').attr('class', $('#main-container').attr('class').replace(/\bsidebar-.*\b/g, '').trim());
        }
        $.cookie(prefix + 'color', color);
    });
    //Handel selected color
    var theme_colors = ["blue", "red", "green", "orange", "yellow", "pink", "magenta", "gray", "black"];
    $.each(theme_colors, function (k, v) {
        if ($('body').hasClass('skin-' + v)) {
            $('#theme-setting ul.colors > li').removeClass('active');
            $('#theme-setting ul.colors > li:has(a.' + v + ')').addClass('active');
        }
    });

    $.each(theme_colors, function (k, v) {
        if ($('#navbar').hasClass('navbar-' + v)) {
            $('#theme-setting ul[data-prefix="navbar-"] > li').removeClass('active');
            $('#theme-setting ul[data-prefix="navbar-"] > li:has(a.' + v + ')').addClass('active');
        }

        if ($('#main-container').hasClass('sidebar-' + v)) {
            $('#theme-setting ul[data-prefix="sidebar-"] > li').removeClass('active');
            $('#theme-setting ul[data-prefix="sidebar-"] > li:has(a.' + v + ')').addClass('active');
        }
    });
    //Handle fixed navbar & sidebar
    if ($('#sidebar').hasClass('sidebar-fixed')) {
        $('#theme-setting > ul > li > a[data-target="sidebar"] > i').attr('class', 'fa fa-check-square-o green')
    }
    if ($('#navbar').hasClass('navbar-fixed')) {
        $('#theme-setting > ul > li > a[data-target="navbar"] > i').attr('class', 'fa fa-check-square-o green')
    }
    $('#theme-setting > ul > li > a').click(function () {
        var target = $(this).data('target');
        var check = $(this).children('i');
        if (check.hasClass('fa-square-o')) {
            check.attr('class', 'fa fa-check-square-o green');
            $('#' + target).addClass(target + '-fixed');
            $.cookie(target + '-fixed', 'true');
        } else {
            check.attr('class', 'fa fa-square-o');
            $('#' + target).removeClass(target + '-fixed');
            $.cookie(target + '-fixed', 'false');
        }
        if (target == "sidebar") {
            if (check.hasClass('fa-square-o')) {
                $("#sidebar ul.nav-list").parent('.slimScrollDiv').replaceWith($("#sidebar ul.nav-list"));
            }
            scrollableSidebar();
        }
    });

    //-------------------------- Boxes -----------------------------//
    $('.box .box-tool > a').click(function (e) {
        if ($(this).data('action') == undefined) {
            return;
        }
        var action = $(this).data('action');
        var btn = $(this);
        switch (action) {
            case 'collapse':
                $(btn).children('i').addClass('anim-turn180');
                $(this).parents('.box').children('.box-content').slideToggle(500, function () {
                    if ($(this).is(":hidden")) {
                        $(btn).children('i').attr('class', 'fa fa-chevron-down');
                    } else {
                        $(btn).children('i').attr('class', 'fa fa-chevron-up');
                    }
                });
                break;
            case 'close':
                $(this).parents('.box').fadeOut(500, function () {
                    $(this).parent().remove();
                })
                break;
            case 'config':
                $('#' + $(this).data('modal')).modal('show');
                break;
        }
        e.preventDefault();
    });

    //-------------------------- Mail Page -----------------------------//
    //Collapse and Uncollapse
    $('.mail-messages .msg-collapse > a').click(function (e) {
        $(this).children('i').addClass('anim-turn180');
        $(this).parents('li').find('.mail-msg-container').slideToggle(500, function () {
            var i = $(this).parents('li').find('.msg-collapse > a').children('i');
            if ($(this).is(':hidden')) {
                $(i).attr('class', 'fa fa-chevron-down');
            } else {
                $(i).attr('class', 'fa fa-chevron-up');
            }
        });
    });

    //Star and Unstar
    $('.mail-content i.fa-star').click(function () {
        $(this).toggleClass('starred');
    });

    //Check All and Uncheck All message in mail list
    $('.mail-toolbar > li:first-child > input[type="checkbox"]').change(function () {
        var check = false;
        if ($(this).is(':checked')) {
            check = true;
        }
        $(this).parents('.mail-content').find('.mail-list .ml-left > input[type="checkbox"]').prop('checked', check);
        var li = $(this).parents('.mail-content').find('.mail-list > li');
        if (check) {
            $(li).addClass('checked');
        } else {
            $(li).removeClass('checked');
        }
    });

    //Add .checked class to selected rows
    $('.mail-list .ml-left > input[type="checkbox"]').change(function () {
        if ($(this).is(':checked')) {
            $(this).parents('li').addClass('checked');
        } else {
            $(this).parents('li').removeClass('checked');
        }
    })

    //--------------------- Go Top Button ---------------------//
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('#btn-scrollup').fadeIn();
        } else {
            $('#btn-scrollup').fadeOut();
        }
    });
    $('#btn-scrollup').click(function () {
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });

    //---------------- Active Tile --------------------//
    if ($('.tile-active').size() > 0) {
        var tileMoveDuration = 1500;
        var tileDefaultStop = 5000;

        var tileGoUp = function (el, stop1, stop2, height) {
            $(el).children('.tile').animate({ top: '-=' + height + 'px' }, tileMoveDuration);
            setTimeout(function () { tileGoDown(el, stop1, stop2, height); }, stop2 + tileMoveDuration);
        }

        var tileGoDown = function (el, stop1, stop2, height) {
            $(el).children('.tile').animate({ top: '+=' + height + 'px' }, tileMoveDuration);
            setTimeout(function () { tileGoUp(el, stop1, stop2, height); }, stop1 + tileMoveDuration);
        }

        $('.tile-active').each(function (index, el) {
            var tile1, tile2, stop1, stop2, height;

            tile1 = $(this).children('.tile').first();
            tile2 = $(this).children('.tile').last();
            stop1 = $(tile1).data('stop');
            stop2 = $(tile2).data('stop');
            height = $(tile1).outerHeight();

            if (stop1 == undefined) {
                stop1 = tileDefaultStop;
            }
            if (stop2 == undefined) {
                stop2 = tileDefaultStop;
            }

            setTimeout(function () { tileGoUp(el, stop1, stop2, height); }, stop1);
        });
    }

    //------------------------- Table --------------------------//
    //Check all and uncheck all table rows
    $('.table > thead > tr > th:first-child > input[type="checkbox"]').change(function () {
        var check = false;
        if ($(this).is(':checked')) {
            check = true;
        }
        $(this).parents('thead').next().find('tr > td:first-child > input[type="checkbox"]').prop('checked', check);
    })

    $('.table > tbody > tr > td:first-child > input[type="checkbox"]').change(function () {
        var check = false;
        if ($(this).is(':checked')) {
            check = true;
        }
        if (!check) {
            $('.table > thead > tr > th:first-child > input[type="checkbox"]').prop('checked', false);
        }
    })

    //------------------------ Data Table -----------------------//

    if (jQuery().dataTable) {
        $('#quan-ly-yeu-cau').dataTable({
            "aLengthMenu": [
                [10, 15, 25, 50, 100, -1],
                [10, 15, 25, 50, 100, "All"]
            ],
            "iDisplayLength": 10,
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sInfo": "_START_ - _END_ of _TOTAL_",
                "sInfoEmpty": "0 - 0 of 0",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [0]
            }]
        });
        $('#danh-sach-yeu-cau').dataTable({
            "aLengthMenu": [
                [10, 15, 25, 50, 100, -1],
                [10, 15, 25, 50, 100, "All"]
            ],
            "iDisplayLength": 10,
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sInfo": "_START_ - _END_ of _TOTAL_",
                "sInfoEmpty": "0 - 0 of 0",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [0]
            }]
        });
        $('#my-request').dataTable({
            "aLengthMenu": [
                [10, 15, 25, 50, 100, -1],
                [10, 15, 25, 50, 100, "All"]
            ],
            "iDisplayLength": 10,
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sInfo": "_START_ - _END_ of _TOTAL_",
                "sInfoEmpty": "0 - 0 of 0",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [0]
            }]
        });
    }

    //------------------------------ Form validation --------------------------//
    if (jQuery().validate) {
        var removeSuccessClass = function (e) {
            $(e).closest('.form-group').removeClass('has-success');
        }
        var $validator = $('#validation-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.next('.chosen-container').length) {
                    error.insertAfter(element.next('.chosen-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",

            invalidHandler: function (event, validator) { //display error alert on form submit
                var el = $(validator.errorList[0].element);
                if ($(el).hasClass('chosen')) {
                    $(el).trigger('chosen:activate');
                } else {
                    $(el).focus();
                }
            },

            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change dony by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                setTimeout(function () { removeSuccessClass(element); }, 3000);
            },

            success: function (label) {
                label.closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
            }
        });
    }

    $('.window-close').on('click', function () {
        var cfm = confirm('Bạn muốn đóng cửa sổ này?');
        if (cfm == true) window.top.close();
    });

    $('table#quan-ly-yeu-cau tbody').bind('click', 'td a', function () {
        if (!$(event.target).is("a")) return false;
        var taget = $(event.target);
        var maYeuCau = taget.attr('data-content');
        var data = new FormData();
        data.append('_token', $('meta[name="csrf-token"]').attr('content'));
        data.append('header', $('meta[name="csrf-token"]').attr('content'));
        data.append('ma_yeu_cau', maYeuCau);

        $.ajax({
            type: 'POST',
            url: '/get-request',
            data: data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            dataType: 'json',
            success: function (response) {

                $('#ma_yeu_cau').text(response['ma_yeu_cau']);
                if (response['user'] !== null) {
                    $('#ho_ten').text(response['user']['name']);
                }
                if (response['user'] !== null) {
                    $('#so_dien_thoai').text(response['user']['dien_thoai']);
                }
                $('#phong_ban').text(response['phong_ban']['ten_phong_ban']);
                $('#do_uu_tien').append('<span class="label label-large ' + uuTienColorClassObj[response['do_uu_tien']] + '">' + uuTienObj[response['do_uu_tien']] + '</span>');
                $('#tieu_de').text(response['tieu_de']);
                $('#noi_dung').html(response['noi_dung']);

                if ($('#ccMaiList').length) {
                    var data = '';
                    if (response['ccMail'].length != null && response['ccMail'].length != undefined) {
                        response['ccMail'].forEach(function (mail, index) {
                            data += mail['name'] + ' < ' + mail['email'] + ' > ' + '<br/>';
                        });
                    }
                    $('#ccMaiList').html(data);
                }

                $('#yeu_cau_xu_ly').html(response['yeu_cau_xu_ly']);
                $('#ngay_xu_ly').text(response['ngay_xu_ly']);
                if (response['xu_ly'] != null) {
                    $('p#nguoi_xu_ly').text(response['xu_ly']['name']);
                }

                if (response['trang_thai'] == YEU_CAU_MOI && response['thong_tin_xu_ly'] != null) {
                    $('#thong_tin_xu_ly_layout').attr('style', '');
                } else {
                    $('#thong_tin_xu_ly_layout').attr('style', 'display:none');
                }

                $('#thong_tin_xu_ly').html(response['thong_tin_xu_ly']);

                if (response['han_xu_ly'] !== null) {
                    // var date = response['han_xu_ly'].replace( /(\d{4})-(\d{2})-(\d{2})/, "$3/$2/$1");
                    var date = new Date(response['han_xu_ly']);
                    $('#han_xu_ly_yc').datepicker('setDate', date);
                }
                if (response['ngay_xu_ly'] !== null) {
                    var date = new Date(response['ngay_xu_ly'])
                    $('#ngay_xu_ly_yc').datepicker('setDate', date);
                }
                if (response['ngay_tao'] !== null) {
                    var date = new Date(response['ngay_tao']);
                    $('#ngay_tao_yc').datepicker('setDate', date);
                }

                $('#nguoi_xu_ly').val(response['nguoi_xu_ly']).trigger("chosen:updated");;


                $('#trang_thai').val(response['trang_thai']);
                if ($('p#trang_thai').length > 0) {
                    $('p#trang_thai').empty();
                    $('p#trang_thai').append('<span class="label label-large ' + trangThaiColorClassObj[response['trang_thai']] + '">' + trangThaiObj[response['trang_thai']] + '</span>');
                }
                $('#loai_yeu_cau').val(response['loai_yeu_cau']);
                $('p#loai_yeu_cau').text(response['loai_yc']['ten_loai_yeu_cau']);

                if (response['files'].length > 0) {
                    var data = '';
                    response['files'].forEach(function (file, index) {
                        data += '<a href="fileDownload/' + file['store_file_name'] + '">' + file['file_name'] + '</a>';
                        if (index < response['files'].length - 1) {
                            data += '<br/>';
                        }
                    });
                    $('.attach-file').append(data);
                }

                if (response['trang_thai'] == TIEP_NHAN || response['trang_thai'] == DANG_XU_LY || response['trang_thai'] == HOAN_THANH || response['trang_thai'] == TU_CHOI) {
                    $('#han_xu_ly').attr('disabled', 'disabled');
                    $('#nguoi_xu_ly').attr('disabled', 'disabled');
                    $('#trang_thai').attr('disabled', 'disabled');
                    $('#ngay_xu_ly').attr('disabled', 'disabled');
                    $('#loai_yeu_cau').attr('disabled', 'disabled');
                    $('#updateRequest').attr('disabled', 'disabled');
                    $('#updateHandleRequest').attr('disabled', 'disabled');
                } else {
                    $('#han_xu_ly').removeAttr('disabled');
                    $('#nguoi_xu_ly').removeAttr('disabled');
                    $('#trang_thai').removeAttr('disabled');
                    $('#ngay_xu_ly').removeAttr('disabled');
                    $('#loai_yeu_cau').removeAttr('disabled');
                    $('#updateRequest').removeAttr('disabled');
                    $('#updateHandleRequest').removeAttr('disabled');
                }
            }
            ,
            error: function (response) {
                return false;
            }
        });
    });

    $("#requestDetail").on('hidden.bs.modal', function () {
        $('#ma_yeu_cau').text("");
        $('#ho_ten').text("");
        $('#so_dien_thoai').text("");
        $('#phong_ban').text("");
        $('#do_uu_tien').text("");
        $('#tieu_de').text("");
        $('#noi_dung').html("");
        //  $('#cc_email').html("");
        $('#loai_yeu_cau p').text("");
        $('#ngay_tao_yc').datepicker('update', '');
        $('#han_xu_ly_yc').datepicker('update', '');
        $('#yeu_cau_xu_ly').text("");
        $('#ngay_xu_ly').text("");
        $('#ngay_xu_ly_yc').datepicker('update', '');
        $('#nguoi_xu_ly p').text("");
        $('#trang_thai option').removeAttr('selected');
        $('.attach-file').empty();
    });

    $('#updateRequest').on('click', function () {
        $('#updateRequest').attr('disabled', true);

        var trang_thai = $('#trang_thai option:selected').val();
        var yeu_cau_xu_ly = CKEDITOR.instances['yeu_cau_xu_ly'].getData();
        var nguoi_xu_ly = $('#nguoi_xu_ly option:selected').val();

        if (nguoi_xu_ly == "") {
            if (trang_thai != HOAN_THANH && trang_thai != TU_CHOI) {
                console.log(trang_thai);
                $('#updateRequest').attr('disabled', false);
                alert('Vui lòng chọn người xử lý.');
                return;
            }
        }

        if (trang_thai == TU_CHOI) {
            if (yeu_cau_xu_ly == "" || yeu_cau_xu_ly == null) {
                $('#updateRequest').attr('disabled', false);
                alert("Yêu cầu nhập lý do từ chối.");
                return;
            }
        }
        var data = new FormData($('#form-request')[0]);
        data.append('ma_yeu_cau', $('#ma_yeu_cau').text());
        data.append('yeu_cau_xu_ly', CKEDITOR.instances['yeu_cau_xu_ly'].getData());

        $.ajax({
            type: 'POST',
            url: '/request-assign-set',
            data: data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            dataType: 'json',
            success: function (response) {
                $('#requestDetail').modal('hide');
                alert(response['Content']);
                window.location.href = window.location.origin + "/request-manage";
            },
            error: function (response) {
                alert('Cập nhật thất bại, vui lòng thử lại.');
                window.location.href = window.location.origin + "/request-manage";
            }
        });
        return false;
    })

    $('table#danh-sach-yeu-cau tbody').bind('click', 'td a', function () {
        if (!$(event.target).is("a")) return false;
        var taget = $(event.target);
        var maYeuCau = taget.attr('data-content');

        var data = new FormData();
        data.append('_token', $('meta[name="csrf-token"]').attr('content'));
        data.append('header', $('meta[name="csrf-token"]').attr('content'));
        data.append('ma_yeu_cau', maYeuCau);
        $.ajax({
            type: 'POST',
            url: '/get-request',
            data: data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            dataType: 'json',
            success: function (response) {

                $('#ma_yeu_cau').text(response['ma_yeu_cau']);
                if (response['user'] !== null) {
                    $('#ho_ten').text(response['user']['name']);
                }
                if (response['user'] !== null) {
                    $('#so_dien_thoai').text(response['user']['dien_thoai']);
                }
                $('#so_dien_thoai').text(response['so_dien_thoai']);
                $('#phong_ban').text(response['phong_ban']['ten_phong_ban']);
                $('#do_uu_tien').append('<span class="label label-large ' + uuTienColorClassObj[response['do_uu_tien']] + '">' + uuTienObj[response['do_uu_tien']] + '</span>');
                $('#tieu_de').text(response['tieu_de']);
                $('#noi_dung').html(response['noi_dung']);

                if ($('#ccMaiList').length) {
                    var data = '';
                    if (response['ccMail'].length != null && response['ccMail'].length != undefined) {
                        response['ccMail'].forEach(function (mail, index) {
                            data += mail['name'] + ' < ' + mail['email'] + ' > ' + '<br/>';
                        });
                    }
                    $('#ccMaiList').html(data);
                }

                $('#yeu_cau_xu_ly').html(response['yeu_cau_xu_ly']);
                $('#nguoi_xu_ly').text(response['xu_ly']['name'])
                CKEDITOR.instances['thong_tin_xu_ly'].setData(response['thong_tin_xu_ly']);

                $('#loai_yeu_cau').val(response['loai_yeu_cau']);
                $('p#loai_yeu_cau').text(response['loai_yc']['ten_loai_yeu_cau']);

                if (response['han_xu_ly'] !== null) {
                    var date = new Date(response['han_xu_ly']);
                    $('#han_xu_ly').datepicker('setDate', date);
                }
                $('#trang_thai option').each(function () {
                    if ($(this).val() == response['trang_thai']) {
                        $(this).attr('selected', 'selected');
                    }
                });
                if (response['ngay_tao'] !== null) {
                    var date = new Date(response['ngay_tao']);
                    $('#ngay_tao_yc').datepicker('setDate', date);
                }
                if (response['han_xu_ly'] !== null) {
                    var date = new Date(response['han_xu_ly']);
                    $('#han_xu_ly').datepicker('setDate', date);
                }

                if (response['files'].length > 0) {
                    var data = '';
                    response['files'].forEach(function (file, index) {
                        data += '<a href="fileDownload/' + file['store_file_name'] + '">' + file['file_name'] + '</a>';
                        if (index < response['files'].length - 1) {
                            data += '<br/>';
                        }
                    });
                    $('.attach-file').append(data);
                }

                $('#thong_tin_xu_ly').text(response['thong_tin_xu_ly']);
                if (response['trang_thai'] == HOAN_THANH || response['trang_thai'] == TU_CHOI) {
                    $('#updateHandleRequest').attr('disabled', 'disabled');
                    $('#trang_thai').attr('disabled', 'disabled');
                } else {
                    $('#updateHandleRequest').removeAttr('disabled');
                    $('#trang_thai').removeAttr('disabled');
                }

                if (response['gia_han'] == 0) {
                    $('#gia_han_yn').bootstrapSwitch('setState', false);
                    $('#gia_han').removeAttr('disabled');
                } else {
                    $('#gia_han_yn').bootstrapSwitch('setState', true);
                    $('#gia_han').attr('disabled', 'disabled');
                    $('#ngay_gia_han').datepicker('setDate', new Date(response['ngay_gia_han']));
                    $('#ngay_gia_han').attr('disabled', 'disabled');
                    CKEDITOR.instances['noi_dung_gia_han'].setData(response['noi_dung_gia_han']);
                }
            },
            error: function (response) {
                return false;
            }
        });
    });

    $("#dsRequestDetail").on('hidden.bs.modal', function () {
        $('#ma_yeu_cau').text("");
        $('#ho_ten').text("");
        $('#so_dien_thoai').text("");
        $('#phong_ban').text("");
        $('#do_uu_tien').text("");
        $('#tieu_de').text("");
        $('#noi_dung').html("");
        $('#loai_yeu_cau p').text("");
        $('#ngay_tao_yc').datepicker('update', '');
        $('#han_xu_ly_yc').datepicker('update', '');
        $('#ngay_gia_han_div').datepicker('update', '');
        $('#yeu_cau_xu_ly').text("");
        $('#ngay_xu_ly').text("");
        $('#ngay_xu_ly_yc').datepicker('update', '');
        $('#nguoi_xu_ly p').text("");
        $('#trang_thai option').removeAttr('selected');
        $('.attach-file').empty();
        //end clear content
    });

    $('#updateHandleRequest').on('click', function () {
        $('#updateHandleRequest').attr('disabled', true);
        var ma_yeu_cau = $('#ma_yeu_cau').text();
        var trang_thai = $('#trang_thai option:selected').val();
        var thong_tin_xu_ly = CKEDITOR.instances['thong_tin_xu_ly'].getData();
        var gia_han = $('#gia_han').is(":checked") ? 1 : 0;
        var ngay_gia_han = $('#ngay_gia_han').val();
        var noi_dung_gia_han = CKEDITOR.instances['noi_dung_gia_han'].getData();

        var data = new FormData($('#form-request')[0]);
        data.append('_token', $('meta[name="csrf-token"]').attr('content'));
        data.append('header', $('meta[name="csrf-token"]').attr('content'));
        data.append('ma_yeu_cau', ma_yeu_cau);
        data.append('trang_thai', trang_thai);
        data.append('thong_tin_xu_ly', thong_tin_xu_ly);
        data.append('gia_han', gia_han);
        data.append('ngay_gia_han', ngay_gia_han);
        data.append('noi_dung_gia_han', noi_dung_gia_han);

        if (trang_thai == 4 || trang_thai == YEU_CAU_MOI || trang_thai == YEU_CAU_MOI) {
            if (thong_tin_xu_ly == "" || thong_tin_xu_ly == null) {
                alert("Yêu cầu nhập lý do từ chối.");
                $('#updateHandleRequest').attr('disabled', false);
                return false;
            }
        }

        $.ajax({
            type: 'POST',
            url: '/request-update-status',
            data: data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            dataType: 'json',
            success: function (response) {
                $('#requestDetail').modal('hide');
                alert(response['Content']);
                location.reload();
            },
            error: function (response) {
                $('#updateHandleRequest').attr('disabled', false);
                alert('Cập nhật thất bại, vui lòng thử lại.');
                //location.reload();
            }
        });
        return false;
    });

    $('table#my-request tbody').bind('click', 'td a', function () {
        if (!$(event.target).is("a")) return false;
        var taget = $(event.target);
        var maYeuCau = taget.attr('data-content');

        var data = new FormData();
        data.append('_token', $('meta[name="csrf-token"]').attr('content'));
        data.append('header', $('meta[name="csrf-token"]').attr('content'));
        data.append('ma_yeu_cau', maYeuCau);
        $.ajax({
            type: 'POST',
            url: '/get-request',
            data: data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            dataType: 'json',
            success: function (response) {

                $('#ma_yeu_cau').text(response['ma_yeu_cau']);
                if (response['user'] !== null) {
                    $('#ho_ten').text(response['user']['name']);
                }
                $('#so_dien_thoai').text(response['so_dien_thoai']);
                $('#phong_ban').text(response['phong_ban']['ten_phong_ban']);
                $('#do_uu_tien').append('<span class="label label-large ' + uuTienColorClassObj[response['do_uu_tien']] + '">' + uuTienObj[response['do_uu_tien']] + '</span>');
                $('#tieu_de').text(response['tieu_de']);
                $('#noi_dung').html(response['noi_dung']);

                if ($('#ccMaiList').length) {
                    var data = '';
                    if (response['ccMail'].length != null && response['ccMail'].length != undefined) {
                        response['ccMail'].forEach(function (mail, index) {
                            data += mail['name'] + ' < ' + mail['email'] + ' > ' + '<br/>';
                        });
                    }
                    $('#ccMaiList').html(data);
                }

                $('#yeu_cau_xu_ly').html(response['yeu_cau_xu_ly'] != null ? response['yeu_cau_xu_ly'] : "Đang cập nhật...");

                $('#nguoi_xu_ly').html(response['xu_ly'] != null ? response['xu_ly']['name'] : "Đang cập nhật...");

                $('#loai_yeu_cau').text(response['loai_yc']['ten_loai_yeu_cau']);
                if (response['ngay_tao'] !== null) {
                    $('#ngay_tao').text(response['ngay_tao'].split(' ')[0].replace(/(\d{4})-(\d{2})-(\d{2})/, "$3/$2/$1"));
                } else {
                    $('#ngay_tao').text("Đang cập nhật...");
                }
                if (response['han_xu_ly'] !== null) {
                    $('#han_xu_ly').text(response['han_xu_ly'].replace(/(\d{4})-(\d{2})-(\d{2})/, "$3/$2/$1"));
                } else {
                    $('#han_xu_ly').text("Đang cập nhật...");
                }
                if (response['ngay_xu_ly'] !== null) {
                    $('#ngay_xu_ly').text(response['ngay_xu_ly'].replace(/(\d{4})-(\d{2})-(\d{2})/, "$3/$2/$1"));
                } else {
                    $('#ngay_xu_ly').text("Đang cập nhật...")
                }
                $('#trang_thai').val(response['trang_thai']);
                if ($('p#trang_thai').length > 0) {
                    $('p#trang_thai').empty();
                    $('p#trang_thai').append('<span class="label label-large ' + trangThaiColorClassObj[response['trang_thai']] + '">' + trangThaiObj[response['trang_thai']] + '</span>');
                }
                if (response['thong_tin_xu_ly'] != null) {
                    $('#thong_tin_xu_ly').html(response['thong_tin_xu_ly']);
                } else {
                    $('#thong_tin_xu_ly').html("Đang cập nhật...");
                }

                if (response['files'].length > 0) {
                    var data = '';
                    response['files'].forEach(function (file, index) {
                        data += '<a href="fileDownload/' + file['store_file_name'] + '">' + file['file_name'] + '</a>';
                        if (index < response['files'].length - 1) {
                            data += '<br/>';
                        }
                    });
                    $('.attach-file').append(data);
                }
            },
            error: function (response) {
                return false;
            }
        });
    });

    $("#myRequestDetail").on('hidden.bs.modal', function () {
        $('#ma_yeu_cau').text("");
        $('#ho_ten').text("");
        $('#so_dien_thoai').text("");
        $('#phong_ban').text("");
        $('#do_uu_tien').text("");
        $('#tieu_de').text("");
        $('#noi_dung').html("");


        $('#yeu_cau_xu_ly').text("");
        $('#loai_yeu_cau').text("");
        $('#han_xu_ly').text("");
        $('#ngay_xu_ly').text("");
        $('#nguoi_xu_ly').text("")
        $('#thong_tin_xu_ly').text("");
        $('.attach-file').empty();
    });

    $('#phong_ban').on('change', function () {
        $('#ho_ten').empty();

        var ma_phong_ban = $('#phong_ban option:selected').val();
        var data = new FormData();
        data.append('_token', $('meta[name="csrf-token"]').attr('content'));
        data.append('header', $('meta[name="csrf-token"]').attr('content'));
        data.append('ma_phong_ban', ma_phong_ban);
        $.ajax({
            type: 'POST',
            url: '/get-user-phong-ban',
            data: data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            dataType: 'json',
            success: function (response) {
                $('#ho_ten').append($('<option>', {
                    value: '',
                    text: ''
                }));
                $.each(response, function (i, res) {
                    $('#ho_ten').append($('<option>', {
                        value: res['username'],
                        text: res['name']
                    }));
                });
                $("#ho_ten").trigger("chosen:updated");
            },
            error: function (response) {
            }
        });
    });

    $('#ho_ten').on('change', function () {

        var username = $('#ho_ten option:selected').val();
        var data = new FormData();
        data.append('_token', $('meta[name="csrf-token"]').attr('content'));
        data.append('header', $('meta[name="csrf-token"]').attr('content'));
        data.append('username', username);
        $.ajax({
            type: 'POST',
            url: '/get-user-info',
            data: data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            dataType: 'json',
            success: function (response) {
                $('#dien_thoai').val(response[0]['dien_thoai']);
                $('#email').val(response[0]['email']);
            },
            error: function (response) {
            }
        });
    });

    $("#request_form").submit(function (e) {
        $('#phong_ban').prop('disabled', false);
        $('#ho_ten').prop('disabled', false);
        $("#btn_submit_request").attr("disabled", true);

        let check = true;
        if ($('#phong_ban').val() == '' && check == true) {
            alert('Vui lòng chọn phòng ban.');
            check = false;
        }
        if ($('#ho_ten').val() == '' && check == true) {
            alert('Vui lòng nhập họ tên.');
            check = false;
        }
        if ($('#email').val() == '' && check == true) {
            var cfm = confirm('Nếu không nhập email bạn không thể nhận thông tin xử lý. Bạn muốn tiếp tục?');
            if (cfm != true) {
                check = false;
            }
        }
        if ($('#loai_yeu_cau option:selected').attr('data-cc-mail-check') == '1' && check == true) {
            if ($('#cc_email').val() == null || $('#cc_email').val() == '') {
                alert('Vui lòng chọn cc mail cho người phê duyệt đối với loại yêu cầu này.');
                check = false;
            }
        }
        if (check == false) {
            $("#btn_submit_request").attr("disabled", false);
            return false;
        }
        return true;
    });

    if ($('#requestAnaly').length) {
        var result = [];
        var date = new Date();
        var totalDate = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        var lastDay = totalDate.getDate();

        //init flot
        var chartColours = ['#00bfdd', '#ff702a', '#a200ff', '#15b74e', '#fe1010', '#5a8022', '#2c7282'];
        //graph options
        var options = {
            grid: {
                show: true,
                aboveData: true,
                color: "#3f3f3f",
                labelMargin: 5,
                axisMargin: 0,
                borderWidth: 0,
                borderColor: null,
                minBorderMargin: 5,
                clickable: true,
                hoverable: true,
                autoHighlight: true,
                mouseActiveRadius: 20
            },
            series: {
                grow: {
                    active: false,
                    stepMode: "linear",
                    steps: 50,
                    stepDelay: true
                },
                lines: {
                    show: true,
                    fill: true,
                    lineWidth: 3,
                    steps: false
                },
                points: {
                    show: true,
                    radius: 4,
                    symbol: "circle",
                    fill: true,
                    borderColor: "#fff"
                }
            },
            legend: {
                position: "ne",
                margin: [0, -25],
                noColumns: 0,
                labelBoxBorderColor: null,
                labelFormatter: function (label, series) {
                    // just add some space to labes
                    return label + '&nbsp;&nbsp;';
                }
            },
            yaxis: { min: 0 },
            xaxis: { ticks: 11, tickDecimals: 0 },
            colors: chartColours,
            shadowSize: 1,
            tooltip: true, //activate tooltip
            tooltipOpts: {
                content: "%s : %y.0",
                defaultTheme: false,
                shifts: {
                    x: -30,
                    y: -50
                }
            }
        };

        var data = new FormData();

        data.append('_token', $('meta[name="csrf-token"]').attr('content'));
        data.append('header', $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            type: 'POST',
            url: '/get-request-data-by-month',
            data: data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            dataType: 'json',
            success: function (response) {
                result = [];
                $.each(response, function (key, val) {
                    var resList = [];
                    for (var i = 1; i <= lastDay; i++) {
                        var sRsl = 0;
                        $.each(val, function (k, v) {
                            if (parseInt(v.ngay) == i) {
                                sRsl = v.cnt;
                            }
                        })
                        resList.push([i, sRsl]);
                    }
                    result.push(resList);
                });
                $.plot($('#requestAnaly'), [
                    {
                        label: "Yêu cầu mới",
                        data: result[0],
                        lines: { fillColor: "rgba(164,214,221,0.2)" },//rgba(164,214,221,0.2) #A4D6DD
                        points: { fillColor: "#00bfdd" }
                    },
                    {
                        label: "Tiếp nhận",
                        data: result[1],
                        lines: { fillColor: "rgba(255,213,183,0.2)" },//rgba(255,213,183,0.2) #FFD5B7
                        points: { fillColor: "#ff702a" }
                    },
                    {
                        label: "Đang xử lý",
                        data: result[2],
                        lines: { fillColor: "rgba(241,175,255,0.2)" },//rgba(233,255,195,0.2) #F1AFFF
                        points: { fillColor: "#a200ff" }
                    },
                    {
                        label: "Hoàn Thành",
                        data: result[3],
                        lines: { fillColor: "rgba(141,183,151,0.2)" },//rgba(141,183,151,0.2) #8DB797
                        points: { fillColor: "#15b74e" }
                    },
                    {
                        label: "Từ chối",
                        data: result[4],
                        lines: { fillColor: "rgba(254,183,192,0.2)" },//rgba(141,183,151,0.2) FEB7C0
                        points: { fillColor: "#fe1010" }
                    },
                ], options);
            },
            error: function (response) {
            }
        });
    }

    $('#span_tu_ngay').on('click', function () {
        return false;
    })

    $('#switch_search_1').on('switch-change', function () {
        var checkbox = $(this).find('input').first();
        if (checkbox.is(':checked') === false) {
            $('#tu_ngay_1').find('input').attr('disabled', 'disabled');
            $('#den_ngay_1').find('input').attr('disabled', 'disabled');
            $("#cbx_loai_yeu_cau").chosen("destroy");
            $('#cbx_loai_yeu_cau').attr('disabled', 'disabled');
            $("#cbx_loai_yeu_cau").chosen();

            getTotalReqByDateAndDept("0");

        } else {
            $('#tu_ngay_1').find('input').removeAttr('disabled');
            $('#den_ngay_1').find('input').removeAttr('disabled');

            $("#cbx_loai_yeu_cau").chosen("destroy");
            $('#cbx_loai_yeu_cau').removeAttr('disabled');
            $("#cbx_loai_yeu_cau").chosen();

            getTotalReqByDateAndDept("1");
        }
    });

    $('#switch_search_2').on('switch-change', function () {
        var checkbox = $(this).find('input').first();
        if (checkbox.is(':checked') === false) {
            $('#tu_ngay_2').find('input').attr('disabled', 'disabled');
            $('#den_ngay_2').find('input').attr('disabled', 'disabled');
            getTotalReqByTypeAndDate("0");
        } else {
            $('#tu_ngay_2').find('input').removeAttr('disabled');
            $('#den_ngay_2').find('input').removeAttr('disabled');
            getTotalReqByTypeAndDate("1");
        }
    });

    $('#gia_han_yn').on('switch-change', function () {
        var checkbox = $(this).find('input').first();
        if (checkbox.is(':checked') === false) {
            $('#ngay_gia_han').attr('disabled', 'disabled');
            $('#div_gh_noi_dung').fadeOut(300);
        } else {
            $('#ngay_gia_han').removeAttr('disabled');
            $('#div_gh_noi_dung').fadeIn(300);
        }
    });

    $('#tu_ngay_1, #den_ngay_1').datepicker().on('changeDate', function () {
        var checked = $('#switch_search_1').find('input').first().is(':checked');
        if (checked == true) {
            getTotalReqByDateAndDept("1");
        } else {
            return false;
        }
    });

    $('#cbx_loai_yeu_cau').on('change', function () {
        var checked = $('#switch_search_1').find('input').first().is(':checked');
        if (checked == true) {
            getTotalReqByDateAndDept("1");
        }
    });

    $('#tu_ngay_2, #den_ngay_2').datepicker().on('changeDate', function () {
        var checked = $('#switch_search_2').find('input').first().is(':checked');
        if (checked == true) {
            getTotalReqByTypeAndDate("1");
        } else {
            return false;
        }
    });

    function getTotalReqByDateAndDept(checked) {
        var tu_ngay = $('#tu_ngay_1').find('input').val();
        var den_ngay = $('#den_ngay_1').find('input').val();
        var loai_yeu_cau = $('#cbx_loai_yeu_cau').val();

        var data = new FormData();
        data.append('_token', $('meta[name="csrf-token"]').attr('content'));
        data.append('header', $('meta[name="csrf-token"]').attr('content'));
        data.append('loai_yeu_cau', loai_yeu_cau);
        data.append('tu_ngay', tu_ngay);
        data.append('den_ngay', den_ngay);
        data.append('checked', checked);
        $.ajax({
            type: 'POST',
            url: '/get-total-req-by-dept-and-date',
            data: data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            dataType: 'json',
            success: function (response) {
                $('#tongYeuCauTheoPhongBan tbody').empty();
                var result = "";
                $.each(response, function (i, res) {
                    if ((i + 1) % 2 == 1) {
                        result += '<tr> <td width="25%">' + res['ten_phong_ban'] + '</td> <td width="25%">' + res['total'] + '</td>';
                    } else {
                        result += '<td width="25%">' + res['ten_phong_ban'] + '</td> <td width="25%">' + res['total'] + '</td> </tr>';
                    }

                });
                $('#tongYeuCauTheoPhongBan tbody').append(result);
            },
            error: function (response) {
                return false;
            }
        });
    }

    function getTotalReqByTypeAndDate(checked) {
        var tu_ngay = $('#tu_ngay_2').find('input').val();
        var den_ngay = $('#den_ngay_2').find('input').val();

        var data = new FormData();
        data.append('_token', $('meta[name="csrf-token"]').attr('content'));
        data.append('header', $('meta[name="csrf-token"]').attr('content'));
        data.append('tu_ngay', tu_ngay);
        data.append('den_ngay', den_ngay);
        data.append('checked', checked);
        $.ajax({
            type: 'POST',
            url: '/get-total-req-by-type-and-date',
            data: data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            dataType: 'json',
            success: function (response) {
                $('#tb_tongYeuCauTheoLoai tbody').empty();
                var result = "";
                $.each(response, function (i, res) {
                    result += '<tr> <td width="50%">' + res['ten_loai_yeu_cau'] + '</td> <td width="50%">' + res['total'] + '</td> </tr>';
                });
                $('#tb_tongYeuCauTheoLoai tbody').append(result);
            },
            error: function (response) {
                return false;
            }
        });
    }

    //fix conflict ckeditor and bootstrap modal
    $.fn.modal.Constructor.prototype.enforceFocus = function () {
        var $modalElement = this.$element;
        $(document).on('focusin.modal', function (e) {
            var $parent = $(e.target.parentNode);
            if ($modalElement[0] !== e.target && !$modalElement.has(e.target).length
                // add whatever conditions you need here:
                &&
                !$parent.hasClass('cke_dialog_ui_input_select') && !$parent.hasClass('cke_dialog_ui_input_text')) {
                $modalElement.focus()
            }
        })
    };

    if ($('#ckfinder-widget-manual').length) {
        CKFinder.widget('ckfinder-widget-manual', {
            width: '100%',
            height: 700,
            id: 'manual_document',
        });
    }
});
