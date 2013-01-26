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
        events.open();
        $.get(config.host + 'lists', function (data) {
            var listsToShow = [];

            function pushList(list) {
                for (var i = 0, l = list.length; i < l; i++) {
                    var item = list[i];
                    listsToShow.push({
                        'id': item.id,
                        'title': item.name,
                        'date': item.date,
                        'desc': item.description
                    });
                }
            }

            pushList(data.owned_lists);
            pushList(data.accessible_lists);

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
