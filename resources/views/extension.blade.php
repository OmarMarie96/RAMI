<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Extension </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>
<body class="antialiased">
<div class="container p-15" style="margin-top: 50px">
    <input type="hidden" id="slug" value="{{$slug}}">
    <div class="row">
        <div class="col-12 form-group">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">User</th>
                    <th scope="col">Prefix</th>
                    <th scope="col">Time Out</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $key => $user)
                    <tr>
                        <th scope="row">{{++$key}}</th>
                        <td>{{$user->name}}</td>
                        <td>{{$user->prefix}}</td>
                        <td>{{$user->time_out_from ." | ". $user->time_out_to}}</td>
                    </tr>
                @endforeach

                </tbody>

            </table>
        </div>
        <div class="col-12 form-group">
            <h4 class="card-title bolder">Numbers</h4>
            <textarea class="form-control form-control-solid-bg mb-2" rows="4" id="numbers"
                      placeholder="263772130753
263775800949
263772130835
263775800463"></textarea>
            <div class="row d-none" id="div_info">
                <div class="col-6">
                    <span> Number: </span>
                    <span id="number" class="text-danger">  </span>
                    |
                    <span> Timeout: </span>
                    <span id="timeout" class="text-primary">  </span>
                    <span> (s)</span>
                    |
                    <span> Remaining: </span>
                    <span id="remaining" class="text-info">  </span>
                </div>

            </div>
        </div>
        <div class="col-md-12 form-group">
            <button class="btn btn-success float-end" id="btn-send">Send</button>
        </div>
    </div>
</div>

<script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>

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
    };

    function startCountdown(seconds) {
        let counter = seconds;
        const interval = setInterval(() => {
            counter--;
            $("#timeout").text(counter)
            if (counter < 0 ) {
                clearInterval(interval);
            }
        }, 1000);
    }
</script>
</body>
</html>
