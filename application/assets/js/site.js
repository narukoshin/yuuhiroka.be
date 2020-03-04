$(document).ready(function(){
    $(window).scroll(function(){
        var test = $(this).scrollTop()
        
    })
    $(window).scroll(function(){
        $(window).closest('section').css('background-color', 'red')
    })
})