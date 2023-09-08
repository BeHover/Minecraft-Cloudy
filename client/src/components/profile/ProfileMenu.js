import TabItem from "../tabs/TabItem";
import React, {useEffect} from "react";
import {getToken, getUser} from "../../services/UserService";
import {useNavigate} from "react-router-dom";

export default function ProfileMenu() {
    const token = getToken();
    const userData = getUser();
    const navigate = useNavigate();

    useEffect(() => {
        if (null === token) {
            return navigate("/login", { replace: true });
        }
    }, []);

    return (
        <div className="profile-menu">
            <p className="profile-menu__title">Меню управления</p>
            <ul className="profile-menu__list">
                <TabItem link="/profile" title="Главная страница" icon="fas fa-home" />
                <TabItem link="/security" title="Настройки безопасности" icon="fas fa-unlock" />
                <TabItem link="/punishments" title="Блокировки аккаунта" icon="fas fa-gavel" />
                <TabItem link="/mylands" title="Управление городом" icon="fas fa-landmark-alt" />
                <TabItem link="/support" title="Служба поддержки" icon="fas fa-hands-helping" />
            </ul>
        </div>
    );
}