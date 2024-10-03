// Funcionalidades / Libs:
import { Routes, Route } from "react-router-dom";

// Pages:
import UserCoordinates from "./pages/user_coordinates";
import GetVoucher from "./pages/get_voucher";


export default function AppRoutes() {
    return (
        <Routes>

            <Route path="/coordenadas-users" element={<UserCoordinates />} />

            <Route path="/get-vouchers/:idUser" element={<GetVoucher />} />
            
        </Routes>
    )
}
