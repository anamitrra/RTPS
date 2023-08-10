/* A demo JS file for testing */

$(document).ready(function () {
    $.get("https://jsonplaceholder.typicode.com/users", function (data, status) {
        
    console.log(status);

    console.log(data);


    });
});
