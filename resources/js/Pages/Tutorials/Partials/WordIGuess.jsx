import {useContext, useEffect, useState} from "react";
import {range} from "lodash";
import {WordContext} from "@/Pages/Tutorials/Context/WordContext.js";


export default function WordGuess({guess}) {
    const word = useContext(WordContext);
    const [result, setResult] = useState(null);
    useEffect(() => {
        function checkGuess(guess, answer) {
            if (!guess) {
                return null;
            }

            let status = [];
            for (let i = 0; i < guess.length; i++) {
                if (guess[i] === answer[i]) {
                    status[i] = {correct: true, incorrect: false};
                } else if (answer.includes(guess[i])) {
                    status[i] = {correct: false, incorrect: true};
                } else {
                    status[i] = {correct: false, incorrect: false};
                }
            }

            return status;
        }

        setResult(checkGuess(guess, word.answer));
    }, [guess, word.answer]);


    return (
        <div className="grid grid-cols-5 gap-8">
            {
                result ? result.map((obj, i) =>
                    <div key={i}
                         className={`${obj.correct ? 'bg-green-600' : 'bg-gray-300'} flex items-center justify-center w-16 h-16 border border-gray-300 rounded-md p-2`}>
                        <span className="text-lg font-semibold">{guess ? guess[i] : ''}</span>
                    </div>
                ) : range(5).map((num, i) =>
                    <div key={i}
                         className="flex items-center justify-center w-16 h-16 border border-gray-300 rounded-md p-2">
                        <span className="text-lg font-semibold">{guess ? guess[i] : ''}</span>
                    </div>)
            }
        </div>
    );
}
