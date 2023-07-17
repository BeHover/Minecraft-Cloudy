import React from "react";

export default function BanPageBlock() {
    return(
        <div className="profile__menu" style={{alignSelf: "start"}}>
            <p className="profile__menu-title">Блокировки аккаунта</p>
            <p className="profile__menu-subtitle">Здесь отображается вся история ваших блокировок</p>
            <p className="profile__menu-text" style={{textAlign: "center"}}>К счастью, у вас пока не было блокировок аккаунта.</p>
        </div>
    );
}