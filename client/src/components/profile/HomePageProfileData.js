import React from "react";

export default function HomePageProfileData({userData}) {
    return(
        <div className="profile-block">
            <p className="profile-block__title">Данные аккаунта</p>
            <p className="profile-block__subtitle">Информация об аккаунте</p>
            <p className="profile-block__text">
                Игровой никнейм:
                <span className="profile-block__text--color-black">{userData.username}</span>
            </p>
            <p className="profile-block__text">
                Почта:
                <span className="profile-block__text--color-black">{userData.email}</span>
                {userData.verified === true ? <i className="fas fa-badge-check profile-block__icon" /> : ""}
            </p>
            <p className="profile-block__text">
                Дата регистрации:
                <span className="profile-block__text--color-black">{userData.created}</span>
            </p>
        </div>
    );
}