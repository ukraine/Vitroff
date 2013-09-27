/**
 * main.js
 *
 * @requirements jQuery
 * @author Vladimir Chmil <vladimir.chmil@gmail.com>
 */

$(function() {
    /* assign translator modal */
    $("#assign_translator").click(function() {
        $("#add_translator_modal").modal({
            minHeight: 400,
            minWidth: 700
        });
    });

    /* close modal */
    $("#closemodal, #add_translator_modal input[type=reset], #delete_translator_modal input[type=reset]").click(function() {
        $.modal.close();
    });

    /* delete translator */
    $(".del_translator").click(function() {
        $("#delete_translator_modal input[name=translator_del_id]").val($(this).attr("data-id"));

        $("#delete_translator_modal").modal({
            minHeight: 180,
            minWidth: 400
        });
    });
});

