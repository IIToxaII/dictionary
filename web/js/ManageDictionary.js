function removeWords(url, id_dictionary, crsf) {
    var keys = $('#grid-widget').yiiGridView('getSelectedRows');
    if (keys.length > 0 && !confirm('Delete?')) return;
    $.ajax({
        method: "POST",
        url: url,
        dataType: "json",
        data: {id_words: keys, id_dictionary: id_dictionary, _csrf: crsf},
        success: function(data) {
            if (data['answer'] == 'success') {
                $("#dictionary-view").html(data['html']);
            }
        }
    })
}

$("#dictionary-view").on('click', '#modal-addWord', function(){
    $(".overlay").fadeIn();
});

$("#dictionary-view").on('click', "#remove", function(){
    removeWords(
        $(this).data('url'),
        $(this).data('id_dictionary'),
        $(this).data('crsf'));
});

$(".close").click(function(){
    $(".overlay").fadeOut();
});

$(document).mouseup(function(e) {
    var popup = $('.popup-dialog');
    if (e.target != popup[0] && popup.has(e.target).length === 0) {
        $(".overlay").fadeOut();
    }
});
