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
    // Closes navigation dropdown if clicking outside the dropdown
    $(document).on('click', e => {
        if ($(e.target).closest(dropdown).length == 0 && dropdown.hasClass('open')){
            $(dropdown).removeClass('open')
        }
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
                        alert(response.message)
                        break
                    // if error is in name input
                    case 'name':
                        $('#contact_name').parent().css('border-color', '#fe5362')
                        alert(response.message)
                        break
                    // if error is in message input
                    case 'message':
                        $('#contact_message').parent().css('border-color', '#fe5362')    
                        alert(response.message)
                        break
                    // if one or more fields are empty
                    case 'one-or-more-fields':
                        let name    = ($('#contact_name').val().length      == 0) ? true : false
                        let email   = ($('#contact_email').val().length     == 0) ? true : false
                        let message = ($('#contact_message').val().length   == 0) ? true : false
                        if (name){
                            $('#contact_name').parent().css('border-color', '#fe5362')
                            alert('Please fill name field!')}
                        else if (email){
                            $('#contact_email').parent().css('border-color', '#fe5362')
                            alert('Please fill email field!')}
                        else if (message){
                            $('#contact_message').parent().css('border-color', '#fe5362')
                            alert('Please fill message field!')}
                        break
                    default:
                        $('.contact_form label').css('border-color', '#fe5362')
                        alert(response.message)
                        break
                }
            // If sending email was successful
            } else {
                $('.contact_form label').css('border-color', '#03a678')
            }
        }).fail(function(){})
    })
    // If fields are empty, button is disabled
    $('form.contact_form').on('input', ev=>{
        let $name = $('#contact_name').val().length
        let $email = $('#contact_email').val().length
        let $message = $('#contact_message').val().length
        if ($name && $email && $message){ // If all fields are filled in then button is not disabled
            $('button').prop('disabled', false)
        } else { // If all fields are not filled in then button is disabled
            $('button').prop('disabled', true)
        }
    })
})