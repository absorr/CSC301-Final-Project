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

    $(".move-list button").click(onClickUseMove);
});

function onClickBattle() {
    var selectedCount = $(".battle-select-check:checked").length;

    if (selectedCount > 0) {
        $("#battle-select").submit();
    }
}

var move_index = 0;
var move_user_id = 0;

function onClickUseMove() {
    var move = MOVES[$(this).attr("data-move-id")];
    move_index = $(this).attr("data-index");
    move_user_id = $(this).closest(".pokemon-card").attr("data-pokemon-id");

    if (!move) return window.alert("ERROR: Move not found for id " + $(this).attr("data-move-id"));

    $("#move-modal-name").html(move['name']);
    $("#move-modal-type").html(move['type']);
    $("#move-modal-class").html(move['class']);
    $("#move-modal-db").html(move['db'] ? move['db'] : '--');
    $("#move-modal-desc").html(move['effect']);

    $("#modalSelectTarget").modal("show");
}

function onSelectTarget() {
    var target_id = $("[name='target']:checked").val();

    if (!target_id) return;

    $.ajax('api/v1/doMove', {
        data: {
            'targetId': target_id,
            'userId': move_user_id,
            'moveIndex': move_index
        },
        success: function (value) {
            if (value) {
                HEALTH[target_id] -= value;

                if (HEALTH[target_id] <= 0) {
                    window.alert('The pokemon fainted!');
                }

                var healthBar = $("[data-pokemon-id='" + target_id + "'] .progress-bar");
                var percent = HEALTH[target_id] / parseInt(healthBar.attr("aria-valuemax"));

                healthBar.attr("aria-valuenow", HEALTH[target_id]).css("width", (percent * 100) + "%");
            }
        }
    });

    $("#modalSelectTarget").modal("hide");
}