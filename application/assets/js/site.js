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
            // If sending message failed
            if (response.error == true) {
                switch(response.element){
                    // If error is in email input
                    case 'email':
                        $('#contact_email').parent().css('border-color', '#fe5362')
                        break
                    // if error is in name input
                    case 'name':
                        $('#contact_name').parent().css('border-color', '#fe5362')
                        break
                    // if error is in message input
                    case 'message': break
                }
            // If sending email was successful
            } else {
                $('.contact_form label').css('border-color', '#03a678')
            }
        }).fail(function(){})
    })
})