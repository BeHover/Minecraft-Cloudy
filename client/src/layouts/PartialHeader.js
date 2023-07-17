import React from "react";
import "../assets/styles/main.css";

export default function PartialHeader() {
    return (
        <header className="header" style={{background: "white"}}>
            <nav className="navbar container">
                <a className="logo" href="/">
                    <img className="logo__image" src="https://mc-cloudy.com/public/favicon.ico" alt="Logo" draggable="false" />
                    <div className="logo__text">
                        <p className="logo__title">CLOUDY</p>
                        <p className="logo__subtitle">Minecraft Project</p>
                    </div>
                </a>

                {/*<button type="button" className="navbar__button" onClick="navbarMenu()">*/}
                {/*    <i id="bars" className="navbar__icon far fa-bars"></i>*/}
                {/*    <i id="times" className="navbar__icon far fa-times"></i>*/}
                {/*</button>*/}

                {/*<script>*/}
                {/*    function navbarMenu() {*/}
                {/*    const menuBtn = document.querySelector(".navbar__button");*/}
                {/*    const menu = document.querySelector(".navbar__content");*/}

                {/*    menuBtn.classList.toggle("is-open");*/}
                {/*    menu.classList.toggle("is-open");*/}
                {/*    document.body.classList.toggle("overflow-hidden");*/}
                {/*}*/}
                {/*</script>*/}

                <div className="navbar__content">

                    <div className="navbar__profile" style={{marginLeft: "auto"}}>
                        {/*{% if not app.user %}*/}
                        {/*<a href="/" className="btn btn--secondary">*/}
                        {/*    Регистрация*/}
                        {/*</a>*/}
                        <a href="/" className="btn btn--primary">
                            Вернуться на главную<i className="icon icon--left fas fa-home-lg-alt"></i>
                        </a>
                        {/*{% else %}*/}
                        {/*<a href="{{ path('profile') }}" className="btn btn--primary">*/}
                        {/*    Мой аккаунт*/}
                        {/*</a>*/}
                        {/*<a href="{{ path('logout') }}" className="btn btn--secondary">*/}
                        {/*    Выйти<i className="far fa-power-off navbar__profile-icon"></i>*/}
                        {/*</a>*/}
                        {/*{% endif %}*/}
                    </div>

                    <div className="navbar__mobile">
                        <ul className="nav__list nav__list--margin">
                            {/*{% if not app.user %}*/}
                            <li className="nav__item">
                                <a className="nav__link" href="/">
                                    <span className="nav__icon"><i className="fas fa-user-plus"></i></span>
                                    <span className="nav__label">Регистрация</span>
                                </a>
                            </li>
                            <li className="nav__item">
                                <a className="nav__link" href="/">
                                    <span className="nav__icon"><i className="fas fa-sign-in-alt"></i></span>
                                    <span className="nav__label">Войти</span>
                                </a>
                            </li>
                            {/*{% else %}*/}
                            {/*<li className="nav__item">*/}
                            {/*    <a className="nav__link {% if app.request.attributes.get('_route') starts with 'profile' %} nav__link--active {% endif %}"*/}
                            {/*       href="{{ path('profile') }}">*/}
                            {/*        <span className="nav__icon"><i className="fas fa-user"></i></span>*/}
                            {/*        <span className="nav__label">Мой аккаунт</span>*/}
                            {/*    </a>*/}
                            {/*</li>*/}
                            {/*<li className="nav__item">*/}
                            {/*    <a className="nav__link" href="{{ path('logout') }}">*/}
                            {/*        <span className="nav__icon"><i className="far fa-power-off"></i></span>*/}
                            {/*        <span className="nav__label">Выйти из аккаунта</span>*/}
                            {/*    </a>*/}
                            {/*</li>*/}
                            {/*{% endif %}*/}
                        </ul>
                    </div>

                    <img src="https://mc-cloudy.com/public/images/mobile-nav.webp" alt="Cloudy Hero" className="navbar__hero"/>
                </div>
            </nav>
        </header>
    );
}