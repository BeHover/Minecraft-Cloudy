import React from "react";

export default function BannerWithButton({title, subtitle, button = null}) {
    const options = button !== null ? "home__subtitle home__subtitle--href" : "home__subtitle";

    return(
        <section className="section section--gray">
            <div className="container home">
                <div className="home__container">
                    <h3 className="home__title">{title}</h3>
                    <div>
                        <p className={options}>{subtitle}</p>
                        {button}
                    </div>
                </div>
            </div>
        </section>
    );
}