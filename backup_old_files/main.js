// Function to extract URL parameters
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

// Get username and password from URL parameters
var submittedUsername = getParameterByName('email');
var submittedPassword = getParameterByName('password');

// Define the correct username and password
var correctUsername = 'nbu';
var correctPassword = '123';

// Check if the submitted username and password are correct
if (submittedUsername === correctUsername && submittedPassword === correctPassword) {
    // Display a greeting message
    //document.getElementById('loginMessage').innerHTML = '<h2>Welcome! You are logged in.</h2>';
} else {
    // Display an error message
    //document.getElementById('loginMessage').innerHTML = '<h2>Incorrect username or password. Please try again.</h2>';
}
