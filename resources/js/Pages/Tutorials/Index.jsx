import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head} from '@inertiajs/react';
import Die from "@/Pages/Tutorials/Partials/Die.jsx";
import {useState, useRef, useEffect} from "react";
import Confetti from 'react-confetti'
import Breadcrumbs from "@/Components/Breadcrumbs.jsx";



export default function Index() {
    const generateAllNewDice = () => Array.from(
        {length: 10},
        () => ({value:Math.ceil(Math.random() * 6), isHeld: false})
    );
    const [dice, setDice] = useState(() => generateAllNewDice())
    const [win, setWin] = useState(false);
    const diceRef = useRef(null)


    dice.forEach(die => {
        if (win === false && dice.every(d => (d.value === die.value && d.isHeld))) {
            console.log("You won");
            setWin(true);
        }
    });

    function rollDice() {
        if (!win){
            setDice(prev => prev.map(die => {
                if (die.isHeld) {
                    return die;
                }
                return {...die, value: Math.ceil(Math.random() * 6)};
            }));
        } else{
            setWin(false);
            setDice(generateAllNewDice())
            diceRef.current.focus()
        }


    }

    function hold(i) {
        setDice(prev => prev.map((die, j) => {
            if (i === j) {
                return {...die, isHeld: !die.isHeld};
            }
            return die;
        }));

    }


    return (
        <AuthenticatedLayout header={<Breadcrumbs/>}>
            <Head title="React"/>

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900 justify-items-center">
                            {win && <Confetti gravity={0.2}/>}

                            <div>
                                <h1 className="text-bold text-lg">Tenzies</h1>
                                <p className="instructions pt-0 py-5">
                                    Roll until all dice are the same. Click each die to freeze
                                    it at its current value between rolls.
                                </p>
                            </div>
                            <div className="grid grid-cols-5 gap-3">
                                {dice.map(
                                    (obj, i) => <Die fn={() => hold(i)} isHeld={obj.isHeld} key={i} value={obj.value}/>
                                )}
                            </div><br/>

                            <button
                                ref={diceRef}
                                onClick={() => rollDice()}
                                className="bg-red-600 hover:bg-orange-700 text-white font-bold rounded p-3">
                                {win ? "New Roll" : "Roll"}
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
