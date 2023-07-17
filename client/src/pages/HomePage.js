import React from "react";
import "../assets/styles/main.css";
import Header from "../layouts/Header";
import Footer from "../layouts/Footer";
import HelpSection from "../components/home/HelpSection";
import HelpItem from "../components/home/HelpItem";
import HomePageBanner from "../components/banners/HomePageBanner";
import InformationSection from "../components/home/InformationSection";
import DownloadFileButton from "../components/UI/buttons/DownloadFileButton";

export default function HomePage() {
    return (
        <div id="react-dom">
            <Header/>
            <main>
                <HomePageBanner/>

                <HelpSection
                    firstCard={<HelpItem icon="fas fa-sign-in-alt" title="Создание аккаунта" text="Нажмите здесь, чтобы перейти к регистрации нового аккаунта" />}
                    secondCard={<HelpItem icon="fas fa-mailbox" title="Подтверждение почты" text="Используйте ссылку в письме для подтверждения почты" />}
                    thirdCard={<HelpItem icon="fas fa-align-center" title="Изучение правил" text="Прочитайте правила сервера перед началом игры" />}
                    fourthCard={<HelpItem icon="fas fa-gamepad-alt" title="Начало приключений" text="Скопируйте IP адрес сервера и окунитесь с головой в игру" />}
                />

                <InformationSection
                    title="История проекта длиной в четыре сезона"
                    firstText="Цивилизация - это сервер с уникальной реализацией градостроения, в котором автоматизированы процессы создания и управления собственным городом, казной, его территориями, поселенцами и их правами. На полноценную реализацию идеи сервера ушло два года."
                    secondText="Реализация системы градостроения направлена на воплощение творческих идей наших игроков! Стройте от малых густонаселённых поселений до крупных городов с широкими улицами и прочей инфраструктурой. Станьте лучшим строителем в своём городе или нанимайтесь на работу в другие поселения. Покорите игровой мир своими сооружениями!"
                    image="https://mc-cloudy.com/public/images/main-01.webp"
                    backgroundColor={true}
                />

                <InformationSection
                    title="Поддержка голосового чата на сервере"
                    firstText="Наш сервер поддерживает работу голосового чата с 3D-аудио и множеством других функций. Мод опциональный, потому что серверная часть сделана через плагин, а клиентская - через мод."
                    secondText="Голосовой чат погружает и сближает с персонажами в игре — вы общаетесь с ними как в реальной жизни. Установите мод на голосовой чат и общайтесь с игроками без сторонних программ."
                    image="https://mc-cloudy.com/public/images/main-03.webp"
                    firstButton={<DownloadFileButton to="https://www.curseforge.com/minecraft/mc-mods/plasmo-voice/download/3863789/file" text="Скачать для Forge" classNames="btn btn--primary" />}
                    secondButton={<DownloadFileButton to="https://www.curseforge.com/minecraft/mc-mods/plasmo-voice/download/3863788/file" text="Fabric версия мода" classNames="btn btn--black" />}
                    flip={true}
                />
            </main>
            <Footer/>
        </div>
    );
}