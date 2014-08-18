$(document).ready(init);

var x, y;
var coord_array = [];
var moving;

function init() {
    $("#draggble").draggable(
        {
            containment: "parent",
            drag: function (event, ui) {
                x = ui.position.top;
                y = ui.position.left;
            },
            start: function(event, ui)
            {
               moving = true;
            },
            stop: function(event, ui)
            {
                moving = false;
            }

        }

    )

    $(document).mouseup(call_server);
    setInterval('getCoords();', 3000);


}
function call_server(event, ui) {

    $.post(
        "index.php",
        {
            X: x,
            Y: y,
            ip: id,
            name: username

        })

}

function getCoords() {
    $.ajax({
            type: 'POST',
            url: "index.php",
            data: {conf: true,
                conf_ip: id,
                moving: moving
                },
            success: function (data) {
                //console.log(data);
                coord_array = data;
                var array = JSON.parse(coord_array);

                console.log(array);
                //console.log(data.length)
                var y=0;
                for (var i = 0; i < array.length; i++) {
                    if(array[i].Name=="Mine")
                    {
                        document.getElementById("draggble").style.top = array[i].X;
                        document.getElementById("draggble").style.left = array[i].Y;
                    }else{
                    document.getElementById("square" + y).style.top = array[i].X;
                    document.getElementById("square" + y).style.left = array[i].Y;
                    y++;
                    }
                }


            },
            datatype: 'JSON',
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        }
    )

}
