import WordGuess from "@/Pages/Tutorials/Partials/WordIGuess.jsx";
import {range} from "lodash";
import {WordContext} from "@/Pages/Tutorials/Context/WordContext.js";
import {useContext} from "react";

export default function WordResults() {
    const word = useContext(WordContext);

    return (
        <div className="space-y-4">
            {range(6).map((num, i) => <WordGuess key={i} guess={word.guesses[num]}/>)}
        </div>
    );
}
