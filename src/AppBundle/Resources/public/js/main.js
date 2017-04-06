$(function () {
    var $body = $('body');
    var $load_pass = $('.load_pass').text();
    var $copy_pass = $('.copy_pass').text();
    var $show_pass = $('.show_pass').text();
    var $hide = $('.hide').text();

    $body.on('click', '.password-hide', function (event) {
        var $link = $(this);
        var $passLink = $link.attr('href');
        $('.password-field').replaceWith('<button href="' + $passLink + '" class="btn btn-sm btn-default password-value">' + $show_pass + '</button>');
        event.preventDefault();
    });

    $body.on('click', '.password-value', function (event) {
        var $link = $(this);
        var $passLink = $link.attr('href');
        $.post($passLink, function (data) {
            var $return = '<div class="input-group password-field"><input type="text" class="form-control input-sm" value="' +
                data + '"><span class="input-group-btn"><button class="btn btn-default btn-sm password-hide" type="button"  href="' +
                $passLink + '">' + $hide + '</button></span></div>';
            $link.replaceWith($return);
        });

        event.preventDefault();
    });


    $body.on('click', '.copy-password', function (event) {
        var $link = $(this);
        var $passLink = $link.attr('href');
        $.post($passLink, function (data) {
            var $return = '<button class="btn btn-primary btn-sm" data-clipboard-text="' + data + '"><span class="glyphicon glyphicon-copy"></span> ' + $copy_pass + '</button>';
            $link.replaceWith($return);

            new Clipboard('.btn', {
                text: function (trigger) {
                    return $(trigger).data('clipboard-text');
                }
            });
        });
        event.preventDefault();
    });

    $body.on('click', '.delete-item', function (event) {
        var $link = $(this);
        var $deleteLink = $link.attr('href');
        console.log($deleteLink);
        $.post($deleteLink, function (data) {
            if (data === 'Deleted!' || data === 'Gelöscht!' || data === 'Supprimé!') {
                $link.replaceWith('<span class="label label-success">' + data + '</span>');
            } else {
                $link.replaceWith('<span class="label label-danger">' + data + '</span>');
            }
        });

        event.preventDefault();
    });


});