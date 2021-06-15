window.addEventListener("load", () => {
    let submit = document.querySelector('#api-submit');
    let name = document.querySelector('#fullName');
    let name_error = document.querySelector('.name_error');
    let message = document.querySelector('#query');
    let message_error = document.querySelector('.message_error');
    let namere = /^[a-zA-Z ]*$/;
    submit.addEventListener('click', (event) => {
        name_error.textContent = "";
        message_error.textContent = "";
        // name validation
        if (name.value === "") {
            name_error.textContent = "A name is required.";
        }else if (!namere.test(name.value)) {
            // check if name only contains letters and spaces
            name_error.textContent = "Only letters and spaces allowed.";
        }
        // message validation
        if (message.value === "") {
            message_error.textContent = "A message is required.";
        }
        if(message_error.textContent !== "" && name_error.textContent !== ""){
            event.preventDefault();
        }
    });
});