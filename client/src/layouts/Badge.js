import React from "react";
import "../assets/styles/main.css";

export default function Badge() {
    return (
        <section className="container badge">
            <div className="badge__content">
                <div className="badge__body">
                    <div className="badge__icon">
                        <i className="far fa-lock"></i>
                    </div>
                    <div className="badge__text">
                        <p className="badge__title">Вы не авторизованы</p>
                        <p className="badge__subtitle">Авторизуйтесь, чтобы получить полный доступ к сайту</p>
                    </div>
                </div>
                <div className="badge__buttons">
                    <a className="btn btn--secondary" href="/">Войти</a>
                    <a className="btn btn--primary" href="/">Создать аккаунт<i className="fas fa-user-plus badge__buttons-icon"></i></a>
                </div>
            </div>
        </section>
    );
}