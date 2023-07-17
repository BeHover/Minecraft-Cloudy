import React from "react";

export default function LoadingBanner({title, firstText, secondText = null, button = null, image}) {
    const firstOptions = secondText === null && button !== null ? "error__subtitle error__subtitle--href" : "error__subtitle";
    const secondOptions = button !== null ? "error__subtitle error__subtitle--href" : "error__subtitle";

    return(
        <section className="error">
            <div className="container">
                <div className="error__container">
                    <img className="error__image" src={image} alt="ErrorPage loading data" draggable={false}/>
                    <div className="error__content">
                        <h3 className="error__title">{title}</h3>
                        <div>
                            <p className={firstOptions} style={secondText && {marginBottom: "10px"}}>{firstText}</p>
                            {secondText && <p className={secondOptions}>{secondText}</p>}
                            {button}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}