// start function
$(document).ready(function() {
    'use strict';

    // dashbord

    $('.toggle-info').click(function(e) {
        $(this).toggleClass('selected').parent().next('.card-body').fadeToggle();
        if ($(this).hasClass('selected')) {
            $(this).html('<i class="fas fa-plus"></i>');
        } else {
            $(this).html('<i class="fas fa-minus"></i>');
        }
    });


    // Calls the selectBoxIt method on your HTML select box and uses the default theme
    $("select").selectBoxIt({
        autoWidth: false
    });

    // this funtion to remove placeholder on fouce and get placeholder on blur
    $('input').focus(function(e) {
        e.preventDefault();
        $(this).attr('place', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function(e) {
        e.preventDefault();
        $(this).attr('placeholder', $(this).attr('place'));
    });

    // this is function use to add astrisk after any input required
    $('input').each(function() {
        if ($(this).attr('required')) {
            $(this).after('<span class="astrisk">*</span>');
        };
    });

    // this is funtion use to show and hide password when pass on  font awesome eye 
    $('.show-pass').hover(function() {

        $('.password').attr('type', 'text');
    }, function() {
        $('.password').attr('type', 'password');
    });

    // this function for confirm button remove
    $('.confirm').click(function(e) {
        return confirm('Are You Sure Remove This Member');
    });

    $('.cat h3').click(function(e) {
        e.preventDefault();
        $(this).next('.full-view').fadeToggle();
    });

    $('.option span').click(function(e) {
        e.preventDefault();
        $(this).addClass('active').siblings('span').removeClass('active');
        if ($(this).data('view') == 'full') {
            $('.cat .full-view').fadeIn();
        } else {
            $('.cat .full-view').fadeOut();
        }
    });

    // when hover on sub categories the delete button is show else hide
    $('.categories .sub-cat li').hover(function() {
        // over
        $(this).find('.del').fadeIn();
    }, function() {
        // out
        $(this).find('.del').fadeOut();
    });
});
// end funtion