import React from "react";

export default function InformationSection({title, firstText, secondText, image, firstButton, secondButton, backgroundColor = false, flip = false}) {
    const backgroundOptions = backgroundColor === true ? "section section--gray" : "section";
    const containerOptions = flip === true ? "about__container about__container--flip" : "about__container";

    return(
        <section className={backgroundOptions}>
            <div className="container about">
                <div className={containerOptions}>
                    <div className="about__text">
                        <h4 className="about__title">{title}</h4>
                        <p className="about__subtitle">{firstText}</p>
                        <p className="about__subtitle">{secondText}</p>
                        {firstButton && secondButton !== null
                            ? <div className="information__buttons">
                                {firstButton && firstButton}
                                {secondButton && secondButton}
                            </div>
                            : ""
                        }
                    </div>
                    <img className="about__image" src={image} alt="Information section" draggable={false} />
                </div>
            </div>
        </section>
    );
}