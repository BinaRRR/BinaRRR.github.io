const email = document.querySelector("#login");
const loginContainer = document.querySelector(".login-container");
const infoBox = document.createElement("div");
infoBox.classList.add("email-create-info");
loginContainer.appendChild(infoBox);
infoBox.innerText = `Placeholder\nPlaceholder`;
infoBox.style.opacity = '0';

email.addEventListener("input", e => {
    if (email.value == "") {
        infoBox.style.opacity = '0';
    }
    else {
        infoBox.style.opacity = '1';
    }
    infoBox.innerText = `Nowy mail:\n ${email.value}@bmail.com`;
    console.log("sm");
});
console.log(email);