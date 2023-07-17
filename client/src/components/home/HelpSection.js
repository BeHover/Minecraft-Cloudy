import React from "react";

export default function HelpSection({firstCard, secondCard, thirdCard, fourthCard}) {
    return (
        <section className="section">
            <div className="container help">
                <h3 className="help__description">Как начать играть?</h3>
                <ul className="help__list">
                    {firstCard}
                    {secondCard}
                    {thirdCard}
                    {fourthCard}
                </ul>
            </div>
        </section>
    );
}