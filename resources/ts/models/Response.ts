import type { Admin } from "./Admin";

export interface Response {
    success: boolean;
    message: string;
}

export interface AuthenticationResponse extends Response {
    token?: string;
    admin?: Admin;
}
