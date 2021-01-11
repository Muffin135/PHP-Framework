var pathname = window.location.pathname; 
switch(pathname) {
    case "/" :
        document.getElementById("link-1").setAttribute("id", "active");
        break;
    case "/test.php" :
        document.getElementById("link-2").setAttribute("id", "active");
        break;
}

//using regex find /test and then look for regex "href="/test" add " id="active"
// <a href="/test.php">Test</a> => <a href="/test.php" id="active">Test</a>