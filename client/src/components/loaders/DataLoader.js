import React from "react";

export default function DataLoader({title, subtitle}) {

    return(
        <div className="error">
            <div className="container">
                <div className="error__container error__container--load">
                    <div className="data-loader"></div>
                    <div className="error__content">
                        <h3 className="error__title">{title}</h3>
                        <div>
                            <p className="error__subtitle">{subtitle}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}