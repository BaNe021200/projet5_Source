function unhideForm()
{
    var button = document.getElementById('uploadButton');
    var hiddenForm = document.getElementById('uploadFormHidden')
    button.addEventListener('click', function (e) {
        e.preventDefault();
        hiddenForm.style.visibility = "visible";

    })
}

function delayedModal(){
    var timeoutID;
    timeoutID = window.setTimeout(showModal(),2000);
}

function showModal() {
    $('#Success').modal('show');
}



















unhideForm();

/*setTimeout(function (e) {
            e.preventDefault();
            $('#Success').modal('show');
        }, 100)*/