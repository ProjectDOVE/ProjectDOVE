/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define(["jquery", "game/websocket"], function ($, ws) {
    function doSomething() { console.log("OPEN") }

    var start = function() {

        ws.on("open", doSomething);
        ws.on("close", function(e){ console.log("connection closed", e);});

        ws.on("message", "string", function(e){ console.log("Chat says:", e.data); });

        ws.set("path", "ws://localhost:8080");
        ws.connect();

        setTimeout(function() {
            ws.send("Hello Chat!");
        }, 500);

    }

    return {
        start: start
    }

});