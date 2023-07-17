import React, {useEffect, useState} from "react";
import "../assets/styles/main.css";
import HeaderNavigateButton from "../components/UI/buttons/HeaderNavigateButton";
import NavigateButtonWithIcon from "../components/UI/buttons/NavigateButtonWithIcon";
import NavbarHomeButton from "../components/UI/buttons/NavbarHomeButton";
import axios from "axios";
import {useNavigate} from "react-router-dom";

export default function Header() {
    const navigate = useNavigate();
    const isLoggedIn = localStorage.getItem("token") !== null;

    const handleLogout = () => {
        localStorage.removeItem("token");
        navigate("/", { replace: true });
    };

    return (
        <header className="header" style={{background: "white"}}>
            <nav className="navbar container">
                <NavbarHomeButton title="CLOUDY" subtitle="Minecraft Project"></NavbarHomeButton>

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
                    <div className="nav">
                        <ul className="nav__list">
                            <li className="nav__item">
                                <HeaderNavigateButton to="/" icon="fas fa-home" text="Главная страница" />
                            </li>
                            <li className="nav__item">
                                <HeaderNavigateButton to="/rules" icon="fas fa-bookmark" text="Правила сервера" />
                            </li>
                            {/*<li className="nav__item">*/}
                            {/*    <HeaderNavigateButton to="/stats" icon="fas fa-heart" text="Статистика" />*/}
                            {/*</li>*/}
                            <li className="nav__item">
                                <HeaderNavigateButton to="/lands" icon="fas fa-landmark-alt" text="Поселения игроков" />
                            </li>
                        </ul>
                    </div>

                    <div className="navbar__profile">
                        {/*{% if not app.user %}*/}
                        {/*<a href="/" className="btn btn--secondary">*/}
                        {/*    Регистрация*/}
                        {/*</a>*/}
                        {isLoggedIn
                            ? <ul className="navbar__profile-list">
                                <li><NavigateButtonWithIcon to="/profile" icon="fas fa-user-edit navbar__profile-icon" text="Профиль" classNames="btn btn--primary" /></li>
                                <li>
                                    <button type="button" onClick={handleLogout} className="btn btn--black navbar__profile-icon">
                                        <span>
                                            Выйти<i className="fas fa-door-open" style={{marginLeft: "8px"}}></i>
                                        </span>
                                    </button>
                                </li>
                            </ul>
                            : <NavigateButtonWithIcon to="/login" icon="fas fa-sign-in-alt navbar__profile-icon" text="Авторизация" classNames="btn btn--primary" />
                        }

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