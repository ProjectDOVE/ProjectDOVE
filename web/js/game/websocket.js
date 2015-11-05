/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define(function () {
    var options = {
        path: undefined,
        binaryType: "arraybuffer",
        autoReconnect: true,
        reconnectTimeout: 3000,
        maxRetries: 5
    }

    var reconnectAttempts = 0;

    var eventHandlers = {
        open: [],
        close: [],
        error: [],
        message: []
    }

    var ws;

    /**
     * public function On()
     *
     * Adds an EventListener to the WebSocket
     *
     * @param <String> event        name of the event
     * @param <Anything> selector   optional. If an event has different types, this can be used to handle only a specific event sub-type
     * @param <Function> handler    function(Event) - handler for the event. gets passed event data, if any exist
     */
    function On(event, selector, handler) {
        var validEvents = ["open", "close", "error", "message"];
        if( validEvents.indexOf(event) == -1) {
            throw "Invalid Event Type. Expected one of 'open', 'close', 'error', 'message'. Got '" + event + "'";
        }

        //if only 2 arguments are passed
        if(typeof selector === "function" && handler === undefined) {
            handler = selector;
            selector = undefined;
        }

        if(typeof handler !== "function") {
            throw "Expected handler to be a function. Got '" + (typeof handler) + "'";
        }


        eventHandlers[event].push({selector: selector, handler: handler});
    }

    /**
     * public function Connect()
     *
     * Initializes connection, reset if connection is open already
     *
     */
    function Connect() {
        if(options.path === undefined) {
            throw "WebSocket Server Path not defined";
        }

        if(ws !== undefined && ws.readyState == WebSocket.OPEN) {
            console.warn("WebSocket connection opened. Closing and reconnecting...");
            ws.close();
        }


        ws = new WebSocket(options.path);
        ws.binaryType = options.binaryType

        ws.onopen = function(e) {
            onOpen(e);
        };
        ws.onclose = function(e) {
            onClose(e);
        };
        ws.onmessage = function(e) {
            onMessage(e);
        };
        ws.onerror = function(e) {
            onError(e);
        };

        starting = false;
    }

    /**
     * private function onOpen()
     *
     * WebSocket connection open handler
     *
     * @param <Event> e  event object containing the details
     */
    function onOpen(e) {
        //we successfully connected, reset reconnect reconnectAttempts
        reconnectAttempts = 0;

        //handle custom event handlers
        if(eventHandlers.open.length === 0) {
            return;
        }

        var i;

        for(i = 0; i < eventHandlers.open.length; i += 1) {
            eventHandlers.open[i].handler(e);
        }
    }

    /**
     * private function onClose()
     *
     * WebSocket close handler
     *
     * @param <Event> e  event object containing the details of why the connection was closed
     */
    function onClose(e) {

        // code 1000: CLOSE_NORMAL, no need to reconnect
        if(options.autoReconnect === true && e.code && e.code !== 1000) {
            if(options.maxRetries !== undefined && reconnectAttempts >= options.maxRetries) {
                throw "Could not re-establish connection after " + reconnectAttempts + " retries";
            }
            if(starting === false) {
                starting = true;
                reconnectAttempts += 1;
                setTimeout(Connect, options.reconnectTimeout);
            }
        }

        //handle custom event handlers
        if(eventHandlers.close.length === 0) {
            return;
        }

        var i;

        for(i = 0; i < eventHandlers.close.length; i += 1) {

            //selector -> event code
            //selector == undefined -> Catch all

            if(eventHandlers.close[i].selector === undefined || (e.code && eventHandlers.close[i].selector === e.code) ) {
                eventHandlers.close[i].handler(e);
            }
        }

    }

    /**
     * private function onError()
     *
     * WebSocket error handler
     *
     * @param <Event> e  event object containing the details of the error (in the case of websockets this is terribly useless)
     */
    function onError(e) {
        //handle custom event handlers
        if(eventHandlers.error.length === 0) {
            return;
        }

        var i;

        for(i = 0; i < eventHandlers.error.length; i += 1) {
            eventHandlers.error[i].handler(e);
        }
    }

    /**
     * private function onMessage()
     *
     * WebSocket message handler
     *
     * @param <Event> e  event object containing the message data
     */
    function onMessage(e) {
        //No custom handlers - no message handling
        if(eventHandlers.message.length === 0) {
            return;
        }

        //determine message type
        var dataType;
        if( (options.binaryType == "arraybuffer" && e.data instanceof ArrayBuffer)
            || (options.binaryType == "blob" && e.data instanceof Blob) ) {
            dataType = "binary";
        } else {
            try { //check if this is JSON
                e.json = JSON.parse(e.data);
                dataType = "json";
            } catch(err) { // if it is not parseable json, it must be a string
                dataType = "string";
            }
        }

        //handle custom event handlers
        for(i = 0; i < eventHandlers.message.length; i += 1) {
            //selector -> data type; possible values(binary, string, json)
            //selector == undefined -> Catch all

            if(eventHandlers.message[i].selector === undefined || (eventHandlers.message[i].selector == dataType) ) {
                eventHandlers.message[i].handler(e);
            }
        }
    }

    /**
     * public function Set()
     *
     * Sets options for the WebSocket
     *
     * @param <String> option   name of the option
     * @param <Anything> value  new value for the option
     */
    /**
     * public function Set()
     *
     * Sets options for the WebSocket
     * WARNING: Some settings only take effect if the socket is restarted
     *
     * @param <Object> option   An object of property-value pairs to set
     */
    function Set(option, value) {
        if( typeof option === "object" ) {
            for(key in option) {
                if(key in options) {
                    options[key] = option[key];
                }
            }
        } else {
            if(option in options) {
                options[option] = value;
            }
        }
    }

    /**
     * public function Send()
     *
     * Send Data through the WebSocket
     *
     * @param <String/ArrayBuffer/Blob> data   to send
     */
    function Send(data) {
        if(IsOpen()) {
            ws.send(data);
        } else {
            console.warn("Could not send data:", data);
        }
    }

    /**
     * public function IsOpen()
     *
     * Check if the WebSocket is ready
     *
     * @return <bool>   True if open
     */
    function IsOpen() {
        return (ws && ws.readyState == WebSocket.OPEN);
    }

    return {
        on: On,
        connect: Connect,
        set: Set,
        send: Send,
        isOpen: IsOpen
    }


});