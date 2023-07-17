import React from "react";

export default function RulesTitle({description, punishment, top = false}) {
    let options = "rules__description";

    if (top === true) {
        options = "rules__description rules__description--top";
    }

    return(
        <div>
            <h3 className={options}>{description}</h3>
            <p className="rules__punishment">{punishment}</p>
        </div>
    );
}