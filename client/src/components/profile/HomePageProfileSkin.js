import React from "react";

export default function HomePageProfileSkin() {
    return(
        <div className="profile__menu">
            <p className="profile__menu-title">Скин персонажа</p>
            <p className="profile__menu-subtitle">Здесь вы можете изменить свой скин</p>
            <div style={{display: "flex", flexDirection: "column"}}>
                <p className="profile__menu-text">Скин должен быть в формате <span>.png</span>.</p>
                <p className="profile__menu-text">Допустимые размеры: <span>64x64</span> или <span>64х32</span>.</p>
                <div style={{display: "flex", justifyContent: "center", marginTop: "10px"}}>
                    <button type="button" className="btn btn--primary promocode__btn">Изменить скин</button>
                </div>
            </div>
        </div>
    );
}