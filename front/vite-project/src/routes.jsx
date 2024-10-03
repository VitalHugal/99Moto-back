import { BrowserRouter as Router, Routes, Route } from "react-router-dom";

// Pages:
import UserCoordinates from "./pages/user_coordinates";
import GetVoucher from "./pages/get_voucher";
import Result from "./pages/result";
import Voucher from "./pages/tela_com_voucher";

export default function AppRoutes() {
    return (
        <Router>
            <Routes>
                <Route path="/" />
                <Route path="/coordenadas-users" element={<UserCoordinates />} />
                <Route path="/get-vouchers" element={<GetVoucher />} />
                <Route path="/result" element={<Result />} />
                <Route path="/voucher" element={<Voucher />} />
            </Routes>
        </Router>
    );
}
