import { AdminAuthenticationHelper } from "./helpers/AdminAuthenticationHelper";

const form = document.getElementById("login-form") as HTMLFormElement;
const message = document.getElementById("message") as HTMLParagraphElement;

form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const email = (document.getElementById("email") as HTMLInputElement).value;
    const password = (document.getElementById("password") as HTMLInputElement)
        .value;

    const res = await AdminAuthenticationHelper.login(email, password);

    if (res.success) {
        window.location.href = `${window.location.origin}/admin/dashboard`;
    } else {
        message.textContent = res.message;
    }
});
