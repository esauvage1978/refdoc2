$(document).ready(function () {

    var file_index = $('#filecount').val() * 1;
    var link_index = $('#linkcount').val() * 1;

    $('#add-file').click(function (e) {
        addItem('file');
        e.preventDefault();
        return false;
    });

    $('#add-link').click(function (e) {
        addItem('link');
        e.preventDefault();
        return false;
    });

    function addItem(type) {
        if (type === 'file') {
            index = file_index;
            file_index++;
            name_label = 'Fichier n°' + (index + 1);
        } else if (type === 'link') {
            index = link_index;
            link_index++;
            name_label = 'Lien n°' + (index + 1);

        }


        var $container = $('#media-add-' + type);
        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, name_label)
            .replace(/__name__/g, index)
        ;
        var $prototype = $(template);

        addDeleteLink($prototype);
        $container.append($prototype);

        if (type === 'link') {
            $('#backpack_backpackLinks_' + index + '_modifyAt').addClass('d-none');
            $('#backpack_backpackLinks_' + index + '_modifyAt_date_month').val(new Date().getMonth()+1);
            $('#backpack_backpackLinks_' + index + '_modifyAt_date_day').val(new Date().getDate());
            $('#backpack_backpackLinks_' + index + '_modifyAt_date_year').val(new Date().getFullYear());
            $('#backpack_backpackLinks_' + index + '_modifyAt_time_hour').val(new Date().getHours());
            $('#backpack_backpackLinks_' + index + '_modifyAt_time_minute').val(new Date().getMinutes());
        }
        if (type === 'file') {
            $('#backpack_backpackFiles_' + index + '_modifyAt').addClass('d-none');
            $('#backpack_backpackFiles_' + index + '_modifyAt_date_month').val(new Date().getMonth()+1);
            $('#backpack_backpackFiles_' + index + '_modifyAt_date_day').val(new Date().getDate());
            $('#backpack_backpackFiles_' + index + '_modifyAt_date_year').val(new Date().getFullYear());
            $('#backpack_backpackFiles_' + index + '_modifyAt_time_hour').val(new Date().getHours());
            $('#backpack_backpackFiles_' + index + '_modifyAt_time_minute').val(new Date().getMinutes());
        }
    }

    function addDeleteLink($prototype) {
        var $deleteLink = $('<a href="#" class="btn btn-sm btn-danger">Supprimer</a>');
        $prototype.append($deleteLink);

        $deleteLink.click(function (e) {
            $prototype.remove();
            e.preventDefault();
            return false;
        });
    }

    $('.media-delete').click(function (e) {
        e.preventDefault();
        var media_url = $(this).attr('data-media');
        var media_id = $(this).attr('data-msg');
        $('#media-' + media_url).remove();
        $('#' + media_id).remove();
    });


    $(".image-pop").on("click", function () {
        $('#imagepreview').attr('src', $(this).attr('src'));
        $('#imagemodal').modal('show');
    });
});


function showOtherFile($index) {
    $('#backpack_edit_backpackFiles_' + $index + '_tempoDate').val(new Date());
    $('#backpackFile_' + $index).removeClass('d-none')

    $('#backpack_backpackFiles_' + $index + '_modifyAt_date_month').val(new Date().getMonth()+1);
    $('#backpack_backpackFiles_' + $index + '_modifyAt_date_day').val(new Date().getDate());
    $('#backpack_backpackFiles_' + $index + '_modifyAt_date_year').val(new Date().getFullYear());
    $('#backpack_backpackFiles_' + $index + '_modifyAt_time_hour').val(new Date().getHours());
    $('#backpack_backpackFiles_' + $index + '_modifyAt_time_minute').val(new Date().getMinutes());

    $('#backpack_backpackFiles_' + $index + '_modifyAt').addClass('d-none');

}

function showOtherLink($index) {
    $('#backpack_backpackLinks_' + $index + '_modifyAt').addClass('d-none');

    $('#backpack_backpackLinks_' + $index + '_modifyAt_date_month').val(new Date().getMonth()+1);
    $('#backpack_backpackLinks_' + $index + '_modifyAt_date_day').val(new Date().getDate());
    $('#backpack_backpackLinks_' + $index + '_modifyAt_date_year').val(new Date().getFullYear());
    $('#backpack_backpackLinks_' + $index + '_modifyAt_time_hour').val(new Date().getHours());
    $('#backpack_backpackLinks_' + $index + '_modifyAt_time_minute').val(new Date().getMinutes());
    $('#backpackLink_' + $index).removeClass('d-none')
}