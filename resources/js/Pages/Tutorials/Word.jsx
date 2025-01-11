import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head} from '@inertiajs/react';
import WordInput from "@/Pages/Tutorials/Partials/WordInput.jsx";
import WordResults from "@/Pages/Tutorials/Partials/WordResults.jsx";
import {useEffect, useState} from "react";
import Confetti from "react-confetti";
import {WordContext} from "@/Pages/Tutorials/Context/WordContext.js";
import Breadcrumbs from "@/Components/Breadcrumbs.jsx";


export default function Index() {
    const [gameStatus, setGameStatus] = useState('running');
    const [guesses, setGuesses] = useState([]);
    const [answer, setAnswer] = useState('AAAAA');

    function handleSubmitGuess(guess) {
        const nextGuesses = [...guesses, guess];
        setGuesses(nextGuesses);

        if (guess === answer) {
            setGameStatus('win');
        } else if (nextGuesses.length > 5) {
            setGameStatus('lose');
        }

    }

    useEffect(() => {
        fetch('/tutorials/generate').then(r => r.json())
            .then(data => {
                console.log(data);
                setAnswer(data.word);
            });

    }, []);


    return (
        <AuthenticatedLayout header={<Breadcrumbs/>}>
            <WordContext.Provider value={{gameStatus, guesses, answer, handleSubmitGuess}}>
                <Head title="WORD Game"/>
                {gameStatus === 'win' && <Confetti/>}

                <div className="py-12">
                    <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div className="p-6 text-gray-900 justify-items-center">

                                <h2 className="mb-3">WORD Game</h2>

                                <WordResults/>

                                <WordInput/>


                            </div>
                        </div>
                    </div>
                </div>
            </WordContext.Provider>
        </AuthenticatedLayout>
    );
}
