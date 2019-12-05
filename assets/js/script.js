//Remove JQuery UI conflicts with Bootstrap
$.widget.bridge('uibutton', $.ui.button);
$.widget.bridge('uitooltip', $.ui.tooltip);

$(function () {
    $(".battle-select-check").click(function () {
        var selectedCount = $(".battle-select-check:checked").length;
        $("#pokemon-count").html(selectedCount);

        if (selectedCount > 0) {
            $("#battle-submit").removeAttr("disabled");
        } else {
            $("#battle-submit").attr("disabled", true);
        }
    });
});

function onClickBattle() {
    var selectedCount = $(".battle-select-check:checked").length;

    if (selectedCount > 0) {
        $("#battle-select").submit();
    }
}