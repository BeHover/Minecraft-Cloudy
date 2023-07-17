import React from "react";
import "../assets/styles/main.css";

export default function Footer() {
    return (
        <footer className="footer">
            <div className="container">
                <h3 className="footer__title">«Сделано с любовью, ведь мы любим то, чем занимаемся»</h3>
                <p className="footer__text">Любое полное или частичное воспроизведение этого сайта строго запрещено.</p>
                <a href="#server">Политика конфиденциальности<i className="footer__icon far fa-external-link-alt"></i></a>
            </div>
        </footer>
    );
}