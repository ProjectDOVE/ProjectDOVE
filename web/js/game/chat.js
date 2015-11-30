/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define(["jquery", "game/websocket"], function ($, ws) {
    function scrollToBottom() {
        var chatHeight = $("#chatMessages").height();
        $("#chatMessagesContainer").scrollTop( chatHeight );
    }

    var start = function() {
        var websocketPath = $("body").data("websocketPath");
        var websocketUser = $("body").data("websocketUser");
        var websocketTicket = $("body").data("websocketTicket");

        //setup chat input key handler
        $("#chatInput").on("keyup", function (e) {
            if (e.keyCode == 13) {
                if(!ws || ws.readyState == WebSocket.CLOSED || ws.readyState == WebSocket.CLOSING || $("#chatInput").val().trim() === "") {
                    e.preventDefault();
                    return;
                }
                ws.send(JSON.stringify({msg: $("#chatInput").val().trim()}));
                $("#chatMessages").append(websocketUser + ": " + $("#chatInput").val().trim() + "<br />");
                $("#chatInput").val("");
                e.preventDefault();
            }
        });


        ws.on("open", function() {
            ws.send(JSON.stringify({user: websocketUser, ticket: websocketTicket}));
        });
        ws.on("close", function(e){ console.log("connection closed", e);});

        ws.on("message", "json", function(e){
            if("user" in e.json) {
                $("#chatMessages").append(e.json.user + ": " + e.json.message + "<br />");
            } else {
                $("#chatMessages").append("<i>"+e.json.message + "</i><br />");
            }

            scrollToBottom();
        });

        ws.set("path", websocketPath);
        ws.connect();


    }

    return {
        start: start
    }

});