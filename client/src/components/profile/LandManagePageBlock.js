import React from "react";

export default function LandManagePageBlock() {
    return(
        <div className="profile__menu" style={{alignSelf: "start"}}>
            <p className="profile__menu-title">Управление городом</p>
            <p className="profile__menu-subtitle">Здесь можно изменить аватарку и описание города</p>
            <p className="profile__menu-text" style={{textAlign: "center"}}>К сожалению, функционал находится в разработке.</p>
        </div>
    );
}