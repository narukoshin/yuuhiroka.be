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
    $('form.contact_form').on('submit', function(){
        $.ajax('send-message', {
            type:'post',
            dataType:'application/json',
            data:$(this).serialize()
        }).done(function(response){
            var response = JSON.parse(response)
            if (response.error) {}
            else {
                console.log('done...')
            }
        }).fail(function(){

        })
    })
})