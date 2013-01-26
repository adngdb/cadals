define(['./config'], function (config) {
    var event = $('.detail').get(0);

    function open(){
        event.open();
    }

    function prepare() {
        event.render = function(item) {
            var self = this;
            $.get(config.host + 'list'+'?id='+item.id, function (data) {
                console.log(data);
                $('.title', self).html(item.get('title'));
                $('.desc', self).html(item.get('desc'));
                $('.date', self).html(item.get('date'));
            });
        };
    }

    return {
        'open': open,
        'prepare' : prepare
    };
});
