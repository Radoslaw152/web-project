(function () {
    let button = document.querySelector('[name="convertByHtmlText"]');
    button.addEventListener('click', convertByText);

    button = document.querySelector('[name="convertByFile"]');
    button.addEventListener('click', convertByFile);
}());

function convertByText(event) {
    let htmlText = document.getElementById('inputHtmlText').value;
    proceedSendingText(htmlText);
}

function convertByFile(event) {

    const file = document.getElementById('inputFile').files[0];
    if (file) {
        const reader = new FileReader();
        reader.readAsText(file, "UTF-8");
        reader.onload = function (evt) {
            proceedSendingText(evt.target.result);
        };
        reader.onerror = function (evt) {
            document.getElementById('errorFiled').innerHTML = "Cannot open or read file!";
            document.getElementById('emmetField').innerHTML = "";
        }
    }
}

function proceedSendingText(htmlText) {
    let result = sendRequest(htmlText);
    if (result.error !== "") {
        document.getElementById('errorFiled').innerHTML = result.error;
        document.getElementById('emmetField').innerHTML = "";
    } else {
        document.getElementById('emmetField').innerHTML = result.response;
        document.getElementById('errorFiled').innerHTML = "";
    }
}

function sendRequest(htmlText) {
    var xhr = new XMLHttpRequest();
    let response = "";
    let error = "";
    xhr.onload = function () {
        if (xhr.status == 200) {
            response = JSON.parse(xhr.responseText).emmet;
        } else {
            error = JSON.parse(xhr.responseText).error;
        }
    };

    xhr.open('POST', 'convert.php/convert', false);
    xhr.send(JSON.stringify({htmlText: htmlText}));

    return {response: response, error: error};
}
