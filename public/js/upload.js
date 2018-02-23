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


function allowSubmit(){
    var form = document.forms['uploadFormHidden1'];
    var formField = form.getElementsByTagName('input').getElementsByTagName('file');
    var buttonFormSubmit = document.getElementById('submitButton');
     if (formField.value == ""){
        buttonFormSubmit.disabled='true';
     }
     else{
         buttonFormSubmit.disabled='false';
     }

}


unhideForm();
allowSubmit();

/*setTimeout(function (e) {
            e.preventDefault();
            $('#Success').modal('show');
        }, 100)*/