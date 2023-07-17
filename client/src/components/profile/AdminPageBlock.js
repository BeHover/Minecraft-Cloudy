import React from "react";

export default function AdminPageBlock() {
    return(
        <div className="profile__menu" style={{alignSelf: "start"}}>
            <p className="profile__menu-title">Панель администратора</p>
            <p className="profile__menu-subtitle">Здесь можно отвечать на обращения в службу поддержки и прочее</p>
            <p className="profile__menu-text" style={{textAlign: "center"}}>К сожалению, функционал находится в разработке.</p>
        </div>
    );
}