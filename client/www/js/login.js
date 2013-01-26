
define(['./config', './events'], function (config, events) {
    var login = $('.login').get(0);

    login.render = function(item) {
        item = item || { id: '', get: function() { return ''; } };

        $('input[name=user_name]', this).val(item.get('user_name'));
        $('input[name=user_password]', this).val(item.get('user_password'));
    };

    function open() {
        login.open();
    }

    function next_view() {
        events.open();
    }

    function is_connected(onsuccess, onfailure) {
        $.get(config.host + 'login', function (data) {
            if (data['status'] === 'Connected') {
                onsuccess.call();
            }
            else {
                onfailure.call();
            }
        });
    }

    function connect(user_name, user_password, onsuccess) {
        $.post(config.host + 'login', {
            'user_name': user_name,
            'user_password': user_password
        }, function (data) {
            if (data['status'] === 'Connected') {
                onsuccess.call();
            }
            else {
                console.log('Error logging in: ' + data['message']);
            }
        });
    }

    $('button.connect', login).click(function() {
        var el = $(login);
        var user_name = el.find('input[name=user_name]').val();
        var user_password = el.find('input[name=user_password]').val();

        connect(user_name, user_password, next_view);
    });

    return {
        'is_connected': is_connected,
        'open': open,
        'next_view': next_view
    };
});
