import HomePage from "../pages/HomePage";
import ErrorPage from "../pages/ErrorPage";
import RulesPage from "../pages/RulesPage";
import LandsPage from "../pages/LandsPage";
import LoginPage from "../pages/LoginPage";
import ProfilePage from "../pages/Profile/ProfilePage";
import RegisterPage from "../pages/RegisterPage";
import ProfileSecurityPage from "../pages/Profile/ProfileSecurityPage";
import ProfilePunishmentsPage from "../pages/Profile/ProfilePunishmentsPage";
import ProfileLandManagePage from "../pages/Profile/ProfileLandManagePage";
import ProfileSupportPage from "../pages/Profile/ProfileSupportPage";

export const routes = [
    {path: '/', element: <HomePage/>, exact: false},
    {path: '/register', element: <RegisterPage/>, exact: false},
    {path: '/login', element: <LoginPage/>, exact: false},
    {path: '/profile', element: <ProfilePage/>, exact: false},
    {path: '/security', element: <ProfileSecurityPage/>, exact: false},
    {path: '/punishments', element: <ProfilePunishmentsPage/>, exact: false},
    {path: '/mylands', element: <ProfileLandManagePage/>, exact: false},
    {path: '/support', element: <ProfileSupportPage/>, exact: false},
    {path: '/rules', element: <RulesPage/>, exact: false},
    {path: '/lands', element: <LandsPage/>, exact: false},
    {path: '*', element: <ErrorPage/>, exact: false}
]