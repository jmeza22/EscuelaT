/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function sendSMS(to, text) {
    if (to !== null && to !== '' && text !== null && text !== '') {
        console.log('Sending to Device.');
        var app = {
            sendSms: function () {
                var number = to.toString(); /* iOS: ensure number is actually a string */
                var message = text.toString();
                console.log("number=" + number + ", message= " + message);

                //CONFIGURATION
                var options = {
                    replaceLineBreaks: false, // true to replace \n by a new line, false by default
                    android: {
                        intent: 'INTENT'  // send SMS with the native android SMS messaging
                                //intent: '' // send SMS without opening any other app
                    }
                };

                var success = function () {
                    console.log('Message sent successfully');
                };
                var error = function (e) {
                    alert('Message Failed:' + e);
                };
                sms.send(number, message, options, success, error);
            }
        };
    }
}

