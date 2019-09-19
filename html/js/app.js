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

        $(this).parents("form").find("#end_time").trigger("change");
    });

    $(document).off("keyup change", ".form-control").on("keyup change", ".form-control", function (e) {
        let dateFormatPattern = new RegExp(/^([0][1-9]|[12][0-9]|[3][0-1])\/([0][1-9]|[1][0-2])\/\d{4}$/);
        switch (this.id) {
            case "start_date":
                if (e.type === "keyup") {
                    if (dateFormatPattern.test($(this).val())) {
                        $(this).removeClass("is-invalid").addClass("is-valid");
                        this.setCustomValidity("");
                    } else {
                        $(this).removeClass("is-valid").addClass("is-invalid");
                        this.setCustomValidity("Date format is not valid, please use (dd/mm/yyyy)");
                    }
                    updateForm($(this));
                }
                break;
            case "end_date":
                if (e.type === "keyup") {
                    let startDate = new Date($(this).parents("form").find("#start_date").val());
                    let endDate = new Date($(this).val());
                    if (dateFormatPattern.test($(this).val()) && endDate > startDate) {
                        $(this).removeClass("is-invalid").addClass("is-valid");
                        this.setCustomValidity("");
                    } else {
                        $(this).removeClass("is-valid").addClass("is-invalid");
                        this.setCustomValidity("Date format is not valid, please use (dd/mm/yyyy)");
                    }
                    updateForm($(this));
                }
                break;
            case "dayofweek":
                if (e.type === "change") {
                    let pattern = new RegExp(/^[1-6]$/);
                    if (pattern.test($(this).val())) {
                        $(this).removeClass("is-invalid").addClass("is-valid");
                        this.setCustomValidity("");
                    } else {
                        $(this).removeClass("is-valid").addClass("is-invalid");
                        this.setCustomValidity("Day of week is not valid. Only days from Monday to Saturday are available");
                    }
                    updateForm($(this));
                }
                break;
            case "start_time":
                if (e.type === "change") {
                    if (parseInt($(this).val()) < 9 || parseInt($(this).val()) > 18) {
                        $(this).removeClass("is-valid").addClass("is-invalid");
                        this.setCustomValidity("Only hours from 9h to 18h are available");
                    } else {
                        $(this).removeClass("is-invalid").addClass("is-valid");
                        this.setCustomValidity("");
                    }
                    updateForm($(this));
                }
                break;
            case "end_time":
                if (e.type === "change") {
                    let start_time = $("#service #start_time").val();
                    if (!$(this).val() || parseInt($(this).val()) < 10 || parseInt($(this).val()) > 19 || parseInt($(this).val()) <= parseInt(start_time)) {
                        $(this).removeClass("is-valid").addClass("is-invalid");
                        this.setCustomValidity("Only hours from 10h to 19h are available and greater than start time");
                    } else {
                        $(this).removeClass("is-invalid").addClass("is-valid");
                        this.setCustomValidity("");
                    }
                    updateForm($(this));
                }
                break;
            case "weeks":
                if (e.type === "keyup") {
                    if (parseInt($(this).val()) > 0 && parseInt($(this).val()) <= 52) {
                        $(this).removeClass("is-invalid").addClass("is-valid");
                        this.setCustomValidity("");
                    } else {
                        $(this).removeClass("is-valid").addClass("is-invalid");
                        this.setCustomValidity("Only 52 weeks as maximum");
                    }
                    updateForm($(this));
                }
                break;
            case "quantity":
                if (e.type === "keyup") {
                    if (parseInt($(this).val()) > 0 && parseInt($(this).val()) <= 99) {
                        $(this).removeClass("is-invalid").addClass("is-valid");
                        this.setCustomValidity("");
                    } else {
                        $(this).removeClass("is-valid").addClass("is-invalid");
                        this.setCustomValidity("Only 99 items as maximum");
                    }
                    updateForm($(this));
                }
                break;
        }
    });

    function updateForm($element)
    {
        let form = $element.parents("form").get(0);
        let $modal = $(form).parents(".modal-content");
        if (form.checkValidity() === false) {
            $modal.find(".modal-footer .btn-primary").prop("disabled", true);
        } else {
            $modal.find(".modal-footer .btn-primary").prop("disabled", false);
        }
    }
})();