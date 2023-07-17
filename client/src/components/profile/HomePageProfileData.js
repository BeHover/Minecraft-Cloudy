import React from "react";

export default function HomePageProfileData({userData}) {
    return(
        <div className="profile__menu">
            <p className="profile__menu-title">Данные аккаунта</p>
            <p className="profile__menu-subtitle">Информация об аккаунте</p>
            <p className="profile__menu-text">Игровой никнейм: <span>{userData.username}</span></p>
            <p className="profile__menu-text">
                Почта: <span>{userData.email}</span>
                {userData.verified === true ? <i className="fas fa-badge-check profile__verify"></i> : ""}
            </p>
            <p className="profile__menu-text">Дата регистрации: <span>{userData.createdAt}</span></p>
            <p className="profile__menu-text">Последние изменения: <span>{userData.updatedAt}</span></p>
        </div>
    );
}