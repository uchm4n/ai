import {useContext, useId, useState} from "react";
import {Link} from '@inertiajs/react';
import {WordContext} from "@/Pages/Tutorials/Context/WordContext.js";

export default function WordInput() {
    const word = useContext(WordContext);
    const [guess, setGuess] = useState('');

    function handleChange(e) {
        e.preventDefault()
        // sanitize value, keep only alpha characters
        setGuess(e.target.value.toUpperCase().replace(/[^A-Z]/g, ''))
    }


    function handleSubmit(e) {
        e.preventDefault()
        if (guess.length > 5 || guess.length < 5) {
            return null
        }
        word.handleSubmitGuess(guess)
        setGuess('')
    }

    return (
        <div className="flex flex-row items-center">
            <form className="space-y-6" onSubmit={handleSubmit}>
                <div>
                    <label htmlFor="guess-word" className="block mt-2 mb-2 text-sm text-gray-700">Input Word:</label>
                    <input
                        required
                        minLength={5}
                        maxLength={5}
                        type="text"
                        id="guess-word"
                        disabled={word.gameStatus !== 'running'}
                        value={guess}
                        className="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter Guess text here"
                        onChange={(e) => handleChange(e)}
                    />

                    {word.gameStatus === 'win'
                        ? <div>
                            <Link href="/tutorials/word"
                                  className="w-full mt-5 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Answer was: {word.answer}
                            </Link>
                        </div>
                        : word.gameStatus === 'lose' ?
                            <Link href="/tutorials/word"
                                  className="w-full mt-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                New Game
                            </Link> : ''
                    }
                </div>
            </form>
        </div>
    )
        ;
}
