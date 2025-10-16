import { AdminAuthenticationHelper } from "./helpers/AdminAuthenticationHelper";
import { DashboardHelper } from "./helpers/DashboardHelper";

function loadElements() {
    createLogoutButton();
}

function createLogoutButton() {
    const button = document.createElement("button");
    button.textContent = "Logout";
    button.id = "logout-button";
    button.className = "btn btn-primary";
    button.addEventListener("click", async () => {
        const res = await AdminAuthenticationHelper.logout();
        if (res?.success) {
            console.log("logged out");
            window.location.href = `${window.location.origin}`;
        } else {
            console.log(`error occurred logging out: ${res?.message}`);
        }
    });
    document.body.appendChild(button);
}

document.addEventListener("DOMContentLoaded", async () => {
    const res = await DashboardHelper.getData();
    if (res?.success) {
        console.log("authenticated");
        loadElements();
    } else {
        console.log(`not authenticated; ${res?.message}`);
    }
});
