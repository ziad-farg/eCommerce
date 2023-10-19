// start function
$(document).ready(function() {
    'use strict';

    // this is function use class active to switch between page login or signup
    $('.head span').click(function(e) {
        e.preventDefault();
        // add class active on the span and remove this class from other span
        $(this).addClass('active').siblings().removeClass('active');
        // hide all form
        $('form').hide();
        // get data class and show the form contant this class
        $('.' + $(this).data('class')).show();
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

    // live card of faild name
    $('.live').keyup(function(e) {
        $($(this).data('class')).text($(this).val());
    });


});
// end funtion

var loadFile = function(event) {
    var image = document.getElementById('output');
    image.src = URL.createObjectURL(event.target.files[0]);
};