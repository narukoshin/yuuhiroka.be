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
    // #contact send message
    $('form.contact_form').on('submit', function(e){
        e.preventDefault()
        $.ajax('send-message', {
            type:'post',
            dataType:'json',
            data:$(this).serialize()
        }).done(function(response){
            console.log(response)
        }).fail(function(){})
    })
})