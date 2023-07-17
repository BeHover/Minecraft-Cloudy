import HomePage from "../pages/HomePage";
import ErrorPage from "../pages/ErrorPage";
import RulesPage from "../pages/RulesPage";
import LandsPage from "../pages/LandsPage";
import LoginPage from "../pages/LoginPage";
import ProfilePage from "../pages/ProfilePage";
import RegisterPage from "../pages/RegisterPage";

export const routes = [
    {path: '/', element: <HomePage/>, exact: false},
    {path: '/register', element: <RegisterPage/>, exact: false},
    {path: '/login', element: <LoginPage/>, exact: false},
    {path: '/profile', element: <ProfilePage/>, exact: false},
    {path: '/rules', element: <RulesPage/>, exact: false},
    {path: '/lands', element: <LandsPage/>, exact: false},
    {path: '*', element: <ErrorPage/>, exact: false}
]