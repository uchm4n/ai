import {useEffect, useState} from "react";

export default function MouseTracker() {
    const [mousePosition, setMousePosition] = useState({x: 0, y: 0});

    function handleMouseMove(e) {
        setMousePosition({
            x: e.clientX,
            y: e.clientY
        });
    }

    useEffect(() => {

        window.addEventListener('mousemove', handleMouseMove);

        return () => {
            window.removeEventListener('mousemove', handleMouseMove);
        }

    }, []);


    return (
        <div className="text-red-500 px-5">x: {mousePosition.x} y: {mousePosition.y}</div>
    )
}