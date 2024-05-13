<script>

    $("#btn-send").click(function () {
        $("#btn-send").prop("disabled", true)
        let numbers = $("#numbers").val()
        if (numbers.trim() !== "") {
            let slug = $("#slug").val()
            var numbersArray = numbers.split("\n").map(Number);

            function processNumberWithDelay(index) {
                if (index < numbersArray.length) {
                    let value = numbersArray[index];
                    let remaining = numbersArray.length - index - 1;
                    $.ajax({
                        type: "GET",
                        url: slug + "/send/",
                        async: false,
                        data: {
                            number: value,
                            remaining: remaining,
                        },
                        success: function (data) {
                            $("#number").text(value)
                            $("#timeout").text(data.timeout)
                            startCountdown(data.timeout);
                            $("#remaining").text(remaining)
                            $("#div_info").removeClass('d-none')
                            timeout = data.timeout * 1000
                        }
                    })
                    setTimeout(function () {
                        processNumberWithDelay(index + 1);
                    }, timeout);
                } else {
                    $("#div_info").addClass('d-none')
                    $("#btn-send").prop("disabled", false)
                }
            }

            processNumberWithDelay(0);
        } else {
            console.log("The numbers input is empty.");
        }


    });

    function startCountdown(seconds) {
        let counter = seconds;
        const interval = setInterval(() => {
            counter--;
            $("#timeout").text(counter)
        }, 1000);
    }

    function startCountdown(seconds) {
        let counter = seconds;
        const interval = setInterval(() => {
            counter--;
            $("#timeout").text(counter)
            if (counter < 0) {
                clearInterval(interval);
            }
        }, 1000);
    }

    $('#table').on('click', '.edit_column_prefix', function (e) {
        if (!$(this).find('input').length > 0) {
            var input = $('<input />', {
                'type': 'text',
                'name': 'unique',
                'value': $(this).html(),
                'class': 'form-control input_edit_prefix',
                'id': $(this).attr('id')
            });
            $(this).empty();
            $(this).append(input);
            input.focus();
        }
    });

    $(document).on('blur', '.input_edit_prefix', function () {
        $(this).parent().append($(this).val());
        $(this).remove();
        $.ajax({
            url: '{{route('extensions.edit')}}',
            method: 'post',
            data: {
                'user_id': $(this).attr('id'),
                'value': $(this).val(),
                'type': 'prefix',
                '_token': '{{ csrf_token() }}',
            },
            success: function (data) {
            }
        });
    })

    $('#table').on('click', '.edit_column_time_out_from', function (e) {
        if (!$(this).find('input').length > 0) {
            var input = $('<input />', {
                'type': 'number',
                'min': 1,
                'name': 'unique',
                'value': $(this).html(),
                'class': 'form-control d-inline input_edit_time_out_from',
                'id': $(this).attr('id'),
                'style': "width: 100px",
            });
            $(this).empty();
            $(this).append(input);
            input.focus();
        }
    });

    $(document).on('blur', '.input_edit_time_out_from', function () {
        $(this).parent().append($(this).val());
        $(this).remove();
        $.ajax({
            url: '{{route('extensions.edit')}}',
            method: 'post',
            data: {
                'user_id': $(this).attr('id'),
                'value': $(this).val(),
                'type': 'time_out_from',
                '_token': '{{ csrf_token() }}',
            },
            success: function (data) {
            }
        });
    })

    $('#table').on('click', '.edit_column_time_out_to', function (e) {
        if (!$(this).find('input').length > 0) {
            var input = $('<input />', {
                'type': 'number',
                'min': 1,
                'name': 'unique',
                'value': $(this).html(),
                'class': 'form-control d-inline input_edit_time_out_to',
                'id': $(this).attr('id'),
                'style': "width: 100px",
            });
            $(this).empty();
            $(this).append(input);
            input.focus();
        }
    });

    $(document).on('blur', '.input_edit_time_out_to', function () {
        $(this).parent().append($(this).val());
        $(this).remove();
        $.ajax({
            url: '{{route('extensions.edit')}}',
            method: 'post',
            data: {
                'user_id': $(this).attr('id'),
                'value': $(this).val(),
                'type': 'time_out_to',
                '_token': '{{ csrf_token() }}',
            },
            success: function (data) {
            }
        });
    })

</script>

