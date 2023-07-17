import React from "react";

export default function SupportPageBlock() {
    return(
        <div className="profile__menu" style={{alignSelf: "start"}}>
            <p className="profile__menu-title">Служба поддержки</p>
            <p className="profile__menu-subtitle">Здесь вы можете попросить помощи или сообщить о нарушителе</p>
            <p className="profile__menu-text" style={{textAlign: "center"}}>К сожалению, функционал находится в разработке.</p>
        </div>
    );
}