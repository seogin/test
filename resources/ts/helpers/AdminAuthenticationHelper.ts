import type { Admin } from "../models/Admin";
import type { Response, AuthenticationResponse } from "../models/Response";

export class AdminAuthenticationHelper {
    private static ADMIN_KEY = "admin";

    private static getCookie(name: string): string | null {
        const match = document.cookie.match(
            new RegExp("(^| )" + name + "=([^;]+)")
        );
        return match ? decodeURIComponent(match[2] ?? "") : null;
    }

    static getXSRFToken() {
        return this.getCookie("XSRF-TOKEN");
    }

    static async login(email: string, password: string): Promise<Response> {
        try {
            await fetch("/sanctum/csrf-cookie", {
                method: "GET",
                credentials: "include", // crucial: allows Laravel to set cookies
            });

            const token = this.getXSRFToken();

            const res = await fetch("/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-XSRF-TOKEN": token ?? "",
                },
                credentials: "include",
                body: JSON.stringify({ email, password }),
            });

            const data: AuthenticationResponse = await res.json();

            if (data.success && data.admin) {
                // localStorage.setItem(this.TOKEN_KEY, data.token);
                localStorage.setItem(
                    this.ADMIN_KEY,
                    JSON.stringify(data.admin)
                );
            }

            return data;
        } catch (error) {
            console.error("Login failed:", error);
            return { success: false, message: `${error}` };
        }
    }

    // private static getToken(): string | null {
    //     return localStorage.getItem(this.TOKEN_KEY);
    // }

    static getAdmin(): Admin | null {
        const admin = localStorage.getItem(this.ADMIN_KEY);
        return admin ? (JSON.parse(admin) as Admin) : null;
    }

    static async logout(): Promise<Response> {
        localStorage.removeItem(this.ADMIN_KEY);
        return this.fetchWithCSRF("/logout", "POST");
    }

    // static async logout(): Promise<Response> {
    //     const token = await this.checkToken();

    //     localStorage.removeItem(this.TOKEN_KEY);
    //     localStorage.removeItem(this.ADMIN_KEY);

    //     try {
    //         const res = await fetch(this.LOGOUT_PATH, {
    //             method: "POST",
    //             headers: {
    //                 "Content-Type": "application/json",
    //                 Accept: "application/json",
    //                 Authorization: `Bearer ${token}`,
    //             },
    //         });

    //         const data: Response = await res.json();

    //         if (res.ok) {
    //             return { success: true, message: `${data.message}` };
    //         } else {
    //             return { success: false, message: `${data.message}` };
    //         }
    //     } catch (error) {
    //         console.error(`Error fetching from ${this.LOGOUT_PATH}:`, error);
    //         return { success: false, message: `${error}` };
    //     }
    // }

    static async fetchWithCSRF<T extends Response>(
        path: string,
        method: "GET" | "POST" | "PUT" | "DELETE",
        body?: any
    ): Promise<T | Response> {
        const token = await this.getXSRFToken();

        try {
            const res = await fetch(path, this.buildRequestInit(token, method, body));

            if (!res.ok) {
                return {
                    success: false,
                    message: `Failed to ${method} from ${path} (status ${res.status})`,
                };
            }

            return (await res.json()) as T;
        } catch (error) {
            console.error(`Error fetching from ${path}:`, error);
            return { success: false, message: "Unexpected error occurred." };
        }
    }

    private static buildRequestInit(
        token: string | null,
        method: "GET" | "POST" | "PUT" | "DELETE",
        body?: any
    ) {
        return {
            method: method,
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-XSRF-TOKEN": token ?? "",
            },
            body:
                method === "GET" || method === "DELETE"
                    ? undefined
                    : JSON.stringify(body),
        } as RequestInit;
    }
    // private static async checkToken(): Promise<Response | string | null> {
    //     const token = this.getToken();

    //     if (!token) {
    //         return {
    //             success: false,
    //             message: "No token found. User not logged in.",
    //         };
    //     }

    //     return token;
    // }
}
