/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define(["jquery", "game/websocket"], function ($, ws) {
    function doSomething() { console.log("OPEN") }

    var start = function() {
        var websocketPath = $("body").data("websocketPath");


        ws.on("open", doSomething);
        ws.on("close", function(e){ console.log("connection closed", e);});

        ws.on("message", "string", function(e){ $("#content").append("Chat says:"+ e.data+"<br />"); });

        ws.set("path", websocketPath);
        ws.connect();

        setTimeout(function() {
            ws.send("Hello Chat!");
        }, 500);

    }

    return {
        start: start
    }

});