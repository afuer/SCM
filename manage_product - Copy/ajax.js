function ajaxLoader(url, pageElement, callMessage) {
      document.getElementById(pageElement).innerHTML = callMessage;
      try {
        req = new XMLHttpRequest(); /* e.g. Firefox */
      } catch(e) {
          try {
          req = new ActiveXObject("Msxml2.XMLHTTP");
   /* some versions IE */
          } catch (e) {
              try {
              req = new ActiveXObject("Microsoft.XMLHTTP");
  /* some versions IE */
              } catch (E) {
                 req = false;
              }
          }
      }

      req.onreadystatechange = function() {responseAJAX(pageElement);};
 	 req.open("GET",url,true);
      req.send(null);

  }

function responseAJAX(pageElement) {
    var output = '';
    if(req.readyState == 4) {
         if(req.status == 200) {
              output = req.responseText;
			  //alert(pageElement);
			  //document.getElementById('Address').checked=true;
			  
			  document.getElementById(pageElement).innerHTML = "";
              document.getElementById(pageElement).innerHTML = output;
			  getNextValue();
            }
       }
   }

function getNextValue(){
	ajaxLoader2('ajaxAction.php?a=2&val=urgent&action=transfer','Autotransfer','<left><img src=images/ajaxLoader.gif></left>');
	}


function ajaxLoader2(url, pageElement, callMessage) {
      document.getElementById(pageElement).innerHTML = callMessage;
      try {
        req = new XMLHttpRequest(); /* e.g. Firefox */
      } catch(e) {
          try {
          req = new ActiveXObject("Msxml2.XMLHTTP");
   /* some versions IE */
          } catch (e) {
              try {
              req = new ActiveXObject("Microsoft.XMLHTTP");
  /* some versions IE */
              } catch (E) {
                 req = false;
              }
          }
      }

      req.onreadystatechange = function() {responseAJAX2(pageElement);};
 	 req.open("GET",url,true);
      req.send(null);

  }

function responseAJAX2(pageElement) {
    var output = '';
    if(req.readyState == 4) {
         if(req.status == 200) {
              output = req.responseText;
			  //alert(pageElement);
			  //document.getElementById('Address').checked=true;
			  
			  document.getElementById(pageElement).innerHTML = "";
              document.getElementById(pageElement).innerHTML = output;
			}
       }
   }
