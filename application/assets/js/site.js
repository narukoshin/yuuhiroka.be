$(document).ready(function(){
    // Navigation dropdown
    var dropdown           = $('.menu-item.dropdown')
    $(dropdown).click(function(){
        if($(this).hasClass('open')){
           return $(this).removeClass('open')
        }
        $('li').removeClass('open')
        $(this).addClass('open')
    })
})