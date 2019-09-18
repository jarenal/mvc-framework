(function () {
    $(document).off("click", ".btn-add").on("click", ".btn-add", function (e) {
        e.preventDefault();
        $(".modal-title").html($(this).data("name"));
        $("#" + $(this).data("category")).modal();
    });

    $(document).off("change", "#start_time").on("change", "#start_time", function (e) {
        let start_time = parseInt($(this).val());

        if (start_time) {
            $("#end_time").prop("disabled", false);
            let options = $("#end_time option");
            options.prop("disabled", false);
            $.each(options, function (index, element) {
                if (parseInt($(element).val()) <= start_time) {
                    $(element).prop("disabled", true);
                }
            });
            $("#end_time option:enabled").first().prop("selected", true);
        } else {
            $("#end_time").prop("disabled", true);
        }
    });
})();