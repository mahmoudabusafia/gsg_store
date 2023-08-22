let chat = window.Echo.join('chat')
    .here((users) => {
        console.log(users);
    })
    .joining((user) => {
        $('#message').append(`<div class="shadow-sm my-5 sm:rounded-lg">
            User ${user.name} joined
        </div>`);
    })
    .leaving((user) => {
        $('#message').append(`<div class="shadow-sm my-5 sm:rounded-lg">
            User ${user.name} leaved
        </div>`);
    })
    .error((error) => {
        console.error(error);
    })
    .listen('MessageSent', (event) => {
        addMessage(event);
    })
    .listenForWhisper('typing', (e) => {
        $('#message').append(`<div class="shadow-sm my-5 sm:rounded-lg">
             ${e.name} is typing..
        </div>`);
    });;


(function ($) {
    $('#chat-form').on('submit', function (event) {
        event.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function (res) {
            $('#chat-form input').val('');
        })
    });
})(jQuery);

function addMessage(event) {
    $('#message').append(`<div class="shadow-sm my-5 sm:rounded-lg">
        ${event.sender.name} : ${event.message}
    </div>`);
}

$('#chat-form input').on('keyup', function () {
    chat.whisper('typing', {
        name: 'Someone'
    });
});