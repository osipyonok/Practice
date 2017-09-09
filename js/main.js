$(function() {
    getMessages = function() {
        var self = this;
        var _sRandom = Math.random();  

        $.getJSON('chat.php?action=get_last_messages' + '&_r=' + _sRandom, function(data){
            if(data.messages) {
                $('.chat_main').html(data.messages);
            }

            // Запускаем снова;
            setTimeout(function(){
               getMessages();
            }, 1000);
        });
    }
    getMessages();
});