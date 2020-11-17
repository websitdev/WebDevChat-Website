const box = document.createElement("div"),
    h1 = document.createElement("h1");

var users = localStorage.getItem("users");
if (users === null) {
    document.getElementById("saved-logins-view").parentElement.setAttribute("hidden", "");
} else {
    users = JSON.parse(users);
    box.style.textAlign = "left";
    h1.style.fontSize = "1.5em";
    h1.innerHTML = "Saved logins";
    box.appendChild(h1);
    users.forEach(user => {
        const item = document.createElement("a");
        item.classList.add("user-element");
        item.innerHTML = user.id;
        item.href = "dashboard.html?id=" + user.id;
        box.appendChild(item);
    });
    showSavedLogins();
}

function showSavedLogins() {
    dialog.children[0].children[0].appendChild(box);
    dialog.querySelector("button").innerHTML = "Close";
    dialog.children[0].style.backgroundColor = "white";
    dialog.style.display = "flex";
    dialog.animate({ opacity: [0, 1] }, { duration: 200, fill: "forwards" });
    dialog.children[0].animate({ transform: ['scale(0)', 'scale(1)'] }, { duration: 300, fill: "forwards", delay: 200 });
    main.style.filter = "blur(5px)";
}