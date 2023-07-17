import React from "react";
import "../assets/styles/main.css";
import Header from "../layouts/Header";
import NavigateButtonWithIcon from "../components/UI/buttons/NavigateButtonWithIcon";
import LoadingBanner from "../components/banners/LoadingBanner";

export default function ErrorPage() {
    return (
        <div id="react-dom" style={{overflow: "hidden", background: "#f7f7f7"}}>
            <Header/>
            <LoadingBanner
                title="К сожалению, эта страница не существует"
                firstText="Вы используете неверную ссылку или наткнулись на несуществующую страницу. Пожалуйста, сообщите нам о найденных ошибках в работе сайта."
                button={<NavigateButtonWithIcon to="/report" text="Сообщить об ошибке" icon="fas fa-flag-alt" classNames="btn btn--red error__href" />}
                image="https://mc-cloudy.com/public/images/mobile-nav.webp"
            />
        </div>
    );
}