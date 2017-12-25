function donators() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     document.getElementsByClassName("table-responsive").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "donators.php", true);
  xhttp.send();
}
	
	