import React from "react";

export default function BannerWithText({title, subtitle, text}) {
    return(
        <section className="section section--gray">
            <div className="container home">
                <div className="home__container">
                    <h3 className="home__title">{title}</h3>
                    <div>
                        <p className="home__subtitle">{subtitle}</p>
                        <p className="home__text">{text}</p>
                    </div>
                </div>
            </div>
        </section>
    );
}