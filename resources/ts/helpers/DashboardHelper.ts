import type { Response } from "../models/Response";
import { AdminAuthenticationHelper } from "./AdminAuthenticationHelper";

export class DashboardHelper {
    private static DASHBOARD_PATH = "/admin/getDashboardData";
    private static UPDATE_ADMIN = "/admin/update/";

    static async getData() {
        return await AdminAuthenticationHelper.fetchWithCSRF(this.DASHBOARD_PATH, "GET");
    }

    static async updateData() {
        // TODO
    }
}
