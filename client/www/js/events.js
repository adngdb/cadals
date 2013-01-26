define(['./config'], function (config) {
    var events = $('.events').get(0);

    function compare(a,b){
        if (a.date < b.date)
            return 1;
        if (a.date > b.date)
            return -1;
        return 0;
    }

    function open() {

        events.renderRow = function (item) {
            var el = $(this);
            el.html(item.get('title'));
            el.addClass(item.get('access_type'));
        }

        events.open();
        $.get(config.host + 'lists', function (data) {
            var listsToShow = [];

            function pushList(list, access_type) {
                for (var i = 0, l = list.length; i < l; i++) {
                    var item = list[i];
                    listsToShow.push({
                        'id': item.id,
                        'title': item.name,
                        'date': item.date,
                        'desc': item.description,
                        'access_type': access_type
                    });
                }
            }

            pushList(data.owned_lists, 'own');
            pushList(data.accessible_lists, 'access');

            // short list by title
            listsToShow.sort(compare);
            for (var i = 0, l = listsToShow.length; i < l; i++) {
                events.add(listsToShow[i]);
            }
        });
    }

    return {
        'open': open
    };
});
