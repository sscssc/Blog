function adb_dtect(){

        var message = "AdBlock Detected, Please disable AdBlock and refresh for enter here.";

            var tryMessage = function() {
                setTimeout(function() {
                    if(!document.getElementsByClassName) return;
                    var ads = document.getElementsByClassName('afs_ads'),
                        ad  = ads[ads.length - 1];

                    if(!ad || ad.innerHTML.length == 0 || ad.clientHeight === 0) {
                       var e = document.body;
                        e.parentNode.removeChild(e);
                        e.innerHTML = "<center>AdBlock Detected, Please disable AdBlock and refresh for enter here.</center>";
                        alert(message);
                        //window.location.href = '[URL of the donate page. Remove the two slashes at the start of thsi line to enable.]';
                    } else {
                        ad.style.display = 'none';
                    }

                }, 2000);
            }

            /* Attach a listener for page load ... then show the message */
            if(window.addEventListener) {
                window.addEventListener('load', tryMessage, false);
            } else {
                window.attachEvent('onload', tryMessage); //IE
            }
    
}