(function () {
    let button = document.querySelector('[name="convertByHtmlText"]');
    button.addEventListener('click', convertByText);

    // button = document.querySelector('[name="convertByFile"]');
    // button.addEventListener('click', convertByFile);
}());

function convertByText(event) {
    let htmlText = document.getElementById('inputHtmlText').value;

    let result = sendRequest(htmlText);

    document.getElementById('emmetField').innerHTML = result;
}

function convertByFile(event) {
    var usernameElement = document.getElementsByName('username')[0];

    if (message === "") {
        sendRequest(username, password);
        message = "Successfully send a request";
    }

    document.getElementById('exitMessage').innerHTML = message;
}

function sendRequest(htmlText) {
    var xhr = new XMLHttpRequest();
    let response = "";
    xhr.onload = function () {
        if (xhr.status == 200) {
            response = xhr.responseText;
        } else {
            console.error(xhr.responseText);
        }
    };

    xhr.open('POST', 'index.php/convert', false);
    xhr.send(JSON.stringify({htmlText: htmlText}));

    return response;
}